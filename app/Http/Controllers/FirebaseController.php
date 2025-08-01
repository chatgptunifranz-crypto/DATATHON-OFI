<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    /**
     * Display the Firebase test page.
     */
    public function index()
    {
        return view('firebase.index');
    }    /**
     * Test Firebase connection (API endpoint).
     */
    public function testConnection()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Conexión con Firebase establecida correctamente',
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Display the Realtime Database with Google Maps.
     */
    public function realtimeMap()
    {
        return view('firebase.realtime-map');
    }
}
