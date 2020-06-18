<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PasienPerKelurahan;
use DateTime;
use DB;

class PetaSebaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tanggal = date("Y-m-d");
        $pasiens = DB::select("SELECT *, SUM(ppln+ppdn+tl+lainya) AS 'total_positif', 
                                SUM(perawatan+sembuh+meninggal) AS 'total_kondisi' 
                                FROM tb_positif WHERE tanggal = '$tanggal' GROUP BY id");
        

        foreach($pasiens as $pasien){
            
            if($pasien->total_positif == 0){
                $pasien->color = '#95FF0A';
            }

            elseif($pasien->tl > 0  && $pasien->perawatan > 0){
                $pasien->color = '#920218'; 
            }
            elseif($pasien->total_positif > 0 && $pasien->perawatan == 0 ){
                $pasien->color = '#64991E'; 
            }
         
            elseif($pasien->ppln > 1 || $pasien->ppdn > 1 && $pasien->tl == 0  && $pasien->perawatan > 0){
                $pasien->color = '#E2556B'; 
            }
            
            elseif($pasien->ppln == 1 || $pasien->ppdn == 1 && $pasien->tl == 0  && $pasien->perawatan > 0){
                $pasien->color = '#E6E708'; 
            }
           
        
            
        }

        $dataKelurahans = array();

        foreach($pasiens as $p){
            $dataKelurahans[$p->kelurahan][] = $p;
        }

        $totals = DB::select("SELECT SUM(ppln+ppdn+tl+lainya) as total from tb_positif WHERE tanggal = '$tanggal'");
        
        $positif = 0;
        foreach($totals as $total){
            $positif = $total->total;
        }

        $date7Before = DB::select(
        "SELECT sum(ppln+ppdn+tl+lainya) as total_7, tanggal from tb_positif where tanggal between adddate(now(),-8) and date(now()) group by tanggal");
        
        $total7 = array();
        
        foreach($date7Before as $d){
           array_push($total7, $d->total_7);
        }

        $date7 = array();

        foreach($date7Before as $d){
           $date = date('d F Y', strtotime($d->tanggal));
           array_push($date7, $date);
        }

        // $tanggal = date('d F Y', strtotime($tanggal));

        return view('peta-sebaran',['dataKelurahans'=> $dataKelurahans,'total'=>$positif,'tanggal' => $tanggal, 'total7'=> $total7, 'date7'=>$date7]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request)
    {
        $tanggal = $request->tanggal;
        $pasiens = DB::select("SELECT *, SUM(ppln+ppdn+tl+lainya) AS 'total_positif', 
                                SUM(perawatan+sembuh+meninggal) AS 'total_kondisi' 
                                FROM tb_positif WHERE tanggal = '$tanggal' GROUP BY id");
        

        foreach($pasiens as $pasien){
            if($pasien->total_positif == 0){
                $pasien->color = '#95FF0A';
            }

            elseif($pasien->tl > 0  && $pasien->perawatan > 0){
                $pasien->color = '#920218'; 
            }
            elseif($pasien->total_positif > 0 && $pasien->perawatan == 0 ){
                $pasien->color = '#64991E'; 
            }
         
            elseif($pasien->ppln > 1 || $pasien->ppdn > 1 && $pasien->tl == 0  && $pasien->perawatan > 0){
                $pasien->color = '#E2556B'; 
            }
            
            elseif($pasien->ppln == 1 || $pasien->ppdn == 1 && $pasien->tl == 0  && $pasien->perawatan > 0){
                $pasien->color = '#E6E708'; 
            }
        }

        $dataKelurahans = array();

        foreach($pasiens as $p){
            $dataKelurahans[$p->kelurahan][] = $p;
        }

        $totals = DB::select("SELECT SUM(ppln+ppdn+tl+lainya) as total from tb_positif WHERE tanggal = '$tanggal'");
        
        $positif = 0;
        foreach($totals as $total){
            $positif = $total->total;
        }

        
        $date7Before = DB::select(
            "SELECT sum(ppln+ppdn+tl+lainya) as total_7, tanggal from tb_positif where tanggal between adddate(now(),-8) and date(now()) group by tanggal");
            
            $total7 = array();
            
            foreach($date7Before as $d){
               array_push($total7, $d->total_7);
            }
    
            $date7 = array();
    
            foreach($date7Before as $d){
               $date = date('d F Y', strtotime($d->tanggal));
               array_push($date7, $date);
            }

        //  return $request->tanggal;  
        //  $tanggalNow = date('d F Y', strtotime($request->$tanggal));
        

        return view('peta-sebaran',['dataKelurahans'=> $dataKelurahans,'total'=>$positif,'tanggal' => $tanggal,'total7'=> $total7, 'date7'=>$date7] );

    }

}
