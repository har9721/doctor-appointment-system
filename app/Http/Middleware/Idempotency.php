<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Idempotency
{
    public function handle(Request $request, Closure $next)
    {
        $idempotency_key = $request->header('Idempotency-Key');
        
        if(!$idempotency_key)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Idempotency key missing'
            ], 400);
        }

        $records = DB::table('idempotency_keys')
                    ->where('key', $idempotency_key)
                    ->first();

        if($records && $records->response)
        {
            return response()->json(
                json_decode(
                    $records->response
                ), 200
            );
        }

        return $next($request);
    }
}
