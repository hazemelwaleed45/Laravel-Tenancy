<?php

namespace App\Http\Controllers;
use Stancl\Tenancy\Database\Models\Domain;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tenant;


use Stancl\Tenancy\Contracts\Tenancy;

class TenantController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'id' => 'required|string|unique:tenants',
            'domain' => 'required|string|unique:domains,domain',
        ]);
    
        // Create the tenant
        $tenant = Tenant::create([
            'id' => $request->id,
            'tenancy_db_name' => $request->id . 'database', // Fixed concatenation
        ]);
    
        // Create the domain
        Domain::create([
            'domain' => $request->domain . '.localhost',
            'tenant_id' => $tenant->id,
        ]);
    
        return response()->json([
            'message' => "Tenant '{$request->id}' created successfully at '{$request->domain}'.",
        ]);
    }

    public function index()
    {
        $tenants = Tenant::with('domains')->get();

        return response()->json($tenants);
    }

    public function showDomains()
    {
        $domains = Domain::on('central')->get(); // Explicitly use the 'central' connection
    
        return response()->json($domains);
    }

    public function delete($id)
    {
        // Find the tenant
        $tenant = Tenant::find($id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found.',
            ], 404);
        }

        // Delete the tenant's domain(s)
        Domain::where('tenant_id', $tenant->id)->delete();

        // Delete the tenant
        $tenant->delete();

        // Optionally: Delete the tenant's database
        // $tenant->database()->delete();

        return response()->json([
            'message' => "Tenant '{$id}' and associated domain(s) deleted successfully.",
        ]);
    }
}