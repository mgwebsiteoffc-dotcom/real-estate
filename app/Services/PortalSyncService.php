<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PortalConfig;
use App\Models\PortalPropertySync;
use App\Models\PortalSyncLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortalSyncService
{
    public function syncProperty(Property $property, PortalConfig $portal)
    {
        // Get or create sync record
        $sync = PortalPropertySync::firstOrCreate([
            'property_id' => $property->id,
            'portal_config_id' => $portal->id,
        ]);

        try {
            $propertyData = $this->mapPropertyData($property, $portal->portal_name);
            
            // Determine if create or update
            $action = $sync->portal_property_id ? 'update' : 'create';
            
            // Call portal API
            $response = $this->callPortalAPI($portal, $action, $propertyData, $sync->portal_property_id);
            
            // Update sync record
            $sync->update([
                'portal_property_id' => $response['property_id'] ?? $sync->portal_property_id,
                'status' => 'synced',
                'last_synced_at' => now(),
                'sync_response' => json_encode($response),
            ]);

            // Log success
            $this->logSync($sync, $action, 'success', $propertyData, $response);

            return ['success' => true, 'message' => 'Property synced successfully'];

        } catch (\Exception $e) {
            // Update sync record with error
            $sync->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            // Log failure
            $this->logSync($sync, $action ?? 'create', 'failed', $propertyData ?? [], null, $e->getMessage());

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function mapPropertyData(Property $property, string $portalName)
    {
        // Base mapping
        $data = [
            'title' => $property->title,
            'description' => $property->description,
            'price' => $property->price,
            'property_type' => $property->type,
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'area' => $property->area,
            'address' => $property->address,
            'city' => $property->city,
            'state' => $property->state,
            'pincode' => $property->pincode,
            'images' => $property->images ?? [],
        ];

        // Portal-specific mapping
        switch ($portalName) {
            case '99acres':
                return $this->map99Acres($data, $property);
            case 'magicbricks':
                return $this->mapMagicBricks($data, $property);
            case 'housing':
                return $this->mapHousing($data, $property);
            default:
                return $data;
        }
    }

    protected function map99Acres($data, $property)
    {
        return [
            'propertyTitle' => $data['title'],
            'propertyDescription' => $data['description'],
            'price' => $data['price'],
            'propertyType' => $data['property_type'],
            'bedroom' => $data['bedrooms'],
            'bathroom' => $data['bathrooms'],
            'carpetArea' => $data['area'],
            'address' => $data['address'],
            'locality' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
        ];
    }

    protected function mapMagicBricks($data, $property)
    {
        return [
            'title' => $data['title'],
            'desc' => $data['description'],
            'price' => $data['price'],
            'type' => $data['property_type'],
            'beds' => $data['bedrooms'],
            'baths' => $data['bathrooms'],
            'sqft' => $data['area'],
            'location' => $data['city'],
        ];
    }

    protected function mapHousing($data, $property)
    {
        return $data; // Housing uses standard format
    }

    protected function callPortalAPI(PortalConfig $portal, string $action, array $data, $propertyId = null)
    {
        $apiKey = $portal->api_key;
        $apiSecret = $portal->api_secret;
        
        // This is a mock implementation - replace with actual portal API calls
        switch ($portal->portal_name) {
            case '99acres':
                return $this->call99AcresAPI($action, $data, $propertyId, $apiKey, $apiSecret);
            case 'magicbricks':
                return $this->callMagicBricksAPI($action, $data, $propertyId, $apiKey, $apiSecret);
            case 'housing':
                return $this->callHousingAPI($action, $data, $propertyId, $apiKey, $apiSecret);
            default:
                throw new \Exception("Portal {$portal->portal_name} not supported");
        }
    }

    protected function call99AcresAPI($action, $data, $propertyId, $apiKey, $apiSecret)
    {
        // Mock implementation - replace with actual 99acres API
        $url = $action === 'create' 
            ? 'https://api.99acres.com/properties' 
            : "https://api.99acres.com/properties/{$propertyId}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'X-API-Secret' => $apiSecret,
        ])->post($url, $data);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('99acres API Error: ' . $response->body());
    }

    protected function callMagicBricksAPI($action, $data, $propertyId, $apiKey, $apiSecret)
    {
        // Mock implementation
        return ['property_id' => 'MB' . rand(1000, 9999), 'status' => 'success'];
    }

    protected function callHousingAPI($action, $data, $propertyId, $apiKey, $apiSecret)
    {
        // Mock implementation
        return ['property_id' => 'HOU' . rand(1000, 9999), 'status' => 'success'];
    }

    protected function logSync($sync, $action, $status, $requestData, $responseData, $errorMessage = null)
    {
        PortalSyncLog::create([
            'portal_property_sync_id' => $sync->id,
            'action' => $action,
            'status' => $status,
            'request_data' => json_encode($requestData),
            'response_data' => json_encode($responseData),
            'error_message' => $errorMessage,
        ]);
    }

    public function removeFromPortal(PortalPropertySync $sync)
    {
        try {
            // Call portal API to remove property
            $this->callPortalAPI(
                $sync->portalConfig,
                'delete',
                [],
                $sync->portal_property_id
            );

            $sync->update(['status' => 'removed']);

            return ['success' => true, 'message' => 'Property removed from portal'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
