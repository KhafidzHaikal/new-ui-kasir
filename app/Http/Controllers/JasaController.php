<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Http\Requests\StoreJasaRequest;
use App\Http\Requests\UpdateJasaRequest;
use App\Models\Pengeluaran;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JasaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('jasa.index');
    }

    public function data()
    {
        try {
            $query = Jasa::query();
            
            // Apply user level filtering
            $userLevel = auth()->user()->level;
            
            if ($userLevel == 4) {
                // Level 4 can see their own data and level 1 data
                $query->join('users', 'jasas.id_user', '=', 'users.id')
                      ->where(function ($q) {
                          $q->where('users.level', 4)
                            ->orWhere('users.level', 1);
                      })
                      ->select('jasas.*');
                      
            } elseif ($userLevel == 5) {
                // Level 5 can only see their own data
                $query->join('users', 'jasas.id_user', '=', 'users.id')
                      ->where('users.level', 5)
                      ->select('jasas.*');
                      
            } else {
                // Other levels (admin) can see all data
            }
            
            $query->orderBy('jasas.id_jasa', 'desc');
            
            // Get count for debugging
            $totalCount = $query->count();
            
            return datatables()
                ->eloquent($query)
                ->addIndexColumn()
                ->addColumn('created_at', function ($jasa) {
                    try {
                        return tanggal_indonesia($jasa->created_at, false);
                    } catch (\Exception $e) {
                        return $jasa->created_at->format('d/m/Y');
                    }
                })
                ->addColumn('nominal', function ($jasa) {
                    try {
                        return format_uang($jasa->nominal);
                    } catch (\Exception $e) {
                        return 'Rp ' . number_format($jasa->nominal, 0, ',', '.');
                    }
                })
                ->addColumn('persen', function ($jasa) {
                    return $jasa->persen . '%';
                })
                ->addColumn('aksi', function ($jasa) {
                    $editBtn = '<button type="button" onclick="editForm(\'' . route('jasa.show', $jasa->id_jasa) . '\')" class="btn btn-primary btn-flat" title="Edit"><i class="fa fa-edit"></i></button>';
                    $printBtn = '<button type="button" onclick="nota(\'' . route('transaksi.jasa', $jasa->id_jasa) . '\')" class="btn btn-warning btn-flat" title="Print"><i class="fa fa-print"></i></button>';
                    $deleteBtn = '<button type="button" onclick="deleteData(\'' . route('jasa.destroy', $jasa->id_jasa) . '\')" class="btn btn-danger btn-flat" title="Delete"><i class="fa fa-trash"></i></button>';
                    
                    return '<div class="btn-group">' . $editBtn . ' ' . $printBtn . ' ' . $deleteBtn . '</div>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
                
        } catch (\Exception $e) {
            
            return response()->json([
                'draw' => request()->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Gagal memuat data jasa: ' . $e->getMessage()
            ], 200); // Return 200 to prevent DataTables error
        }
    }

    /**
     * Debug method untuk troubleshooting data jasa
     */
    public function debug()
    {
        try {
            $debug = [
                'user_info' => [
                    'id' => auth()->id(),
                    'level' => auth()->user()->level,
                    'name' => auth()->user()->name ?? 'N/A'
                ],
                'database_info' => [
                    'total_jasa_records' => Jasa::count(),
                    'total_user_records' => \App\Models\User::count(),
                ],
                'sample_data' => [
                    'latest_jasa' => Jasa::latest('id_jasa')->first(),
                    'user_levels' => \App\Models\User::select('level')->distinct()->pluck('level')->toArray()
                ]
            ];
            
            // Test query berdasarkan level user
            $userLevel = auth()->user()->level;
            
            if ($userLevel == 4) {
                $query = Jasa::join('users', 'jasas.id_user', '=', 'users.id')
                      ->where(function ($q) {
                          $q->where('users.level', 4)
                            ->orWhere('users.level', 1);
                      })
                      ->select('jasas.*');
                      
                $debug['query_info'] = [
                    'type' => 'Level 4 filter',
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'count' => $query->count(),
                    'sample_records' => $query->limit(3)->get()
                ];
                
            } elseif ($userLevel == 5) {
                $query = Jasa::join('users', 'jasas.id_user', '=', 'users.id')
                      ->where('users.level', 5)
                      ->select('jasas.*');
                      
                $debug['query_info'] = [
                    'type' => 'Level 5 filter',
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'count' => $query->count(),
                    'sample_records' => $query->limit(3)->get()
                ];
                
            } else {
                $query = Jasa::query();
                
                $debug['query_info'] = [
                    'type' => 'No filter (admin)',
                    'sql' => $query->toSql(),
                    'bindings' => $query->getBindings(),
                    'count' => $query->count(),
                    'sample_records' => $query->limit(3)->get()
                ];
            }
            
            return response()->json($debug, 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreJasaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'deskripsi' => 'required|string|max:255',
                'nominal' => 'required|numeric|min:0',
                'persen' => 'required|numeric|min:0|max:100'
            ]);

            // Simpan data ke tabel jasas (bukan pengeluaran)
            $jasa = new Jasa();
            $jasa->deskripsi = $request->deskripsi;
            $jasa->nominal = $request->nominal;
            $jasa->persen = $request->persen;
            $jasa->id_user = auth()->id();
            $jasa->save();

            return response()->json([
                'success' => true,
                'message' => 'Data jasa berhasil disimpan'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error saving jasa: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data jasa'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jasa = Jasa::find($id);

        return response()->json($jasa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $jasa = Jasa::find($id);
            
            if (!$jasa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jasa tidak ditemukan'
                ], 404);
            }

            return response()->json($jasa, 200);

        } catch (\Exception $e) {
            \Log::error('Error getting jasa for edit: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jasa'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateJasaRequest  $request
     * @param  \App\Models\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'deskripsi' => 'required|string|max:255',
                'nominal' => 'required|numeric|min:0',
                'persen' => 'required|numeric|min:0|max:100'
            ]);

            // Cari data jasa
            $jasa = Jasa::find($id);
            
            if (!$jasa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jasa tidak ditemukan'
                ], 404);
            }

            // Update data
            $jasa->deskripsi = $request->deskripsi;
            $jasa->nominal = $request->nominal;
            $jasa->persen = $request->persen;
            $jasa->save();

            return response()->json([
                'success' => true,
                'message' => 'Data jasa berhasil diperbarui'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error updating jasa: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data jasa'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $jasa = Jasa::find($id);
            
            if (!$jasa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jasa tidak ditemukan'
                ], 404);
            }

            $jasa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data jasa berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error deleting jasa: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data jasa'
            ], 500);
        }
    }

    public function pdf($text, $awal, $akhir)
    {
        $akhir = Carbon::parse($akhir)->endOfDay();
        if ($text == 'cuci') {
            $title = 'Jasa Cuci';
            if (auth()->user()->level == 4) {
                $jasas = DB::table('jasas')
                    ->join('users', 'jasas.id_user', '=', 'users.id')
                    ->where([['users.level', 4], ['jasas.deskripsi', 'Jasa Cuci']])
                    ->select('jasas.*')
                    ->whereBetween('jasas.created_at', [$awal, $akhir])
                    ->get();
            } elseif (auth()->user()->level == 5) {
                $jasas = DB::table('jasas')
                    ->join('users', 'jasas.id_user', '=', 'users.id')
                    ->where([['users.level', 5], ['jasas.deskripsi', 'Jasa Cuci']])
                    ->select('jasas.*')
                    ->whereBetween('jasas.created_at', [$awal, $akhir])
                    ->get();
            } else {
                $jasas = DB::table('jasas')
                    ->where('jasas.deskripsi', 'Jasa Cuci')
                    ->select('jasas.*')
                    ->whereBetween('jasas.created_at', [$awal, $akhir])
                    ->get();
            }
        } elseif ($text == 'service') {
            $title = 'Jasa Service';
            if (auth()->user()->level == 4) {
                $jasas = DB::table('jasas')
                    ->join('users', 'jasas.id_user', '=', 'users.id')
                    ->where([['users.level', 4], ['jasas.deskripsi', 'Jasa Service']])
                    ->select('jasas.*')
                    ->whereBetween('jasas.created_at', [$awal, $akhir])
                    ->get();
            } elseif (auth()->user()->level == 5) {
                $jasas = DB::table('jasas')
                    ->join('users', 'jasas.id_user', '=', 'users.id')
                    ->where([['users.level', 5], ['jasas.deskripsi', 'Jasa Service']])
                    ->select('jasas.*')
                    ->whereBetween('jasas.created_at', [$awal, $akhir])
                    ->get();
            } else {
                $jasas = DB::table('jasas')
                    ->where('jasas.deskripsi', 'Jasa Service')
                    ->select('jasas.*')
                    ->whereBetween('jasas.created_at', [$awal, $akhir])
                    ->get();
            }
        }

        $jumlah = 0;
        foreach ($jasas as $item) {
            $jumlah += $item->nominal;
        }
        return view('jasa.pdf', [
            'awal' => $awal,
            'akhir' => $akhir,
            'jasas' => $jasas,
            'jumlah' => $jumlah,
            'title' => $title
        ]);
    }

    public function nota($id)
    {
        $setting = Setting::first();
        $waktu = Carbon::parse(date(now()))->translatedFormat('d F Y H:i:s');
        $detail = Jasa::find($id);

        return view('jasa.nota', compact('setting', 'detail', 'waktu'));
    }
}
