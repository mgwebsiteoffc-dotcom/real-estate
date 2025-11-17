<?php

namespace App\Http\Controllers;

use App\Models\PortalConfig;
use App\Models\Property;
use App\Models\PortalPropertySync;
use App\Services\PortalSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalIntegrationController extends Controller
{
    protected $syncService;

    public function __construct(PortalSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    public function index()
    {
        $portals = PortalConfig::where('company_id', Auth::user()->company_id)->get();
        $availablePortals = [
            '99acres' => '99acres',
            'magicbricks' => 'MagicBricks',
            'housing' => 'Housing.com',
            'nobroker' => 'NoBroker',
        ];
        
        return view('portals.index', compact('portals', 'availablePortals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'portal_name' => 'required|string',
            'api_key' => 'required|string',
            'api_secret' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        PortalConfig::create([
            'company_id' => Auth::user()->company_id,
            'portal_name' => $validated['portal_name'],
            'api_key' => $validated['api_key'],
            'api_secret' => $validated['api_secret'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Portal configuration saved successfully!');
    }

    public function toggle(PortalConfig $portalConfig)
    {
        $portalConfig->update(['is_active' => !$portalConfig->is_active]);
        
        $status = $portalConfig->is_active ? 'enabled' : 'disabled';
        return back()->with('success', "Portal {$status} successfully!");
    }

    public function destroy(PortalConfig $portalConfig)
    {
        $portalConfig->delete();
        return back()->with('success', 'Portal configuration deleted successfully!');
    }

    public function syncProperty(Request $request, Property $property)
    {
        $validated = $request->validate([
            'portal_id' => 'required|exists:portal_configs,id',
        ]);

        $portal = PortalConfig::findOrFail($validated['portal_id']);
        
        if (!$portal->is_active) {
            return back()->with('error', 'Portal is not active. Please enable it first.');
        }

        $result = $this->syncService->syncProperty($property, $portal);

        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function syncStatus(Property $property)
    {
        $syncs = PortalPropertySync::with(['portalConfig', 'logs'])
            ->where('property_id', $property->id)
            ->get();

        $activePortals = PortalConfig::where('company_id', Auth::user()->company_id)
            ->where('is_active', true)
            ->get();

        return view('portals.sync-status', compact('property', 'syncs', 'activePortals'));
    }

    public function removeSync(PortalPropertySync $sync)
    {
        $result = $this->syncService->removeFromPortal($sync);
        
        return back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}
