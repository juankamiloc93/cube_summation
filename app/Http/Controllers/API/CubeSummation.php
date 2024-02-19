<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CubeSummation extends Controller
{

    function initCube($n){
 
        for($x=1; $x<=$n; $x++){
            for($y=1; $y<=$n; $y++){
                for($z=1; $z<=$n; $z++){
                    $cube[$x][$y][$z] = 0;
                }
            }
        }
        return $cube;
    }


    function calSum($cube, $x1, $y1, $z1, $x2, $y2, $z2){
        $sum = 0;
        for($x=$x1; $x<=$x2; $x++){
            for($y=$y1; $y<=$y2; $y++){
                for($z=$z1; $z<=$z2; $z++){
                    $sum += $cube[$x][$y][$z];
                }
            }
        }
        return $sum ;
    }


    function cubeSum($n, $operations) {
      
        $response = array();                
        $cube = $this->initCube($n);    
     
        foreach($operations as $operation){        
           
            $instruction = explode(' ', $operation);
            $typeInstruction = $instruction[0];      
           
            switch($typeInstruction){
                case 'UPDATE':
                    $cube
                        [$instruction[1]]
                        [$instruction[2]]
                        [$instruction[3]]
                    = $instruction[4];                              
                    break;
               
                case 'QUERY':                
                    $response[] = $this->calSum(
                                    $cube,
                                    $instruction[1],
                                    $instruction[2],
                                    $instruction[3],
                                    $instruction[4],
                                    $instruction[5],
                                    $instruction[6]
                                );
            }          
           
        }
       
        return $response;    
    
    }



    public function executeCubeSummation(Request $request)
    {

        try{            

            $request->validate([
                'input' => 'required|array',               
            ]);

            $inputArray = $request->input;

            $cases = $inputArray[0];

            $rowIndex = 0;

            $res = array();

            for ($casesIndex = 0; $casesIndex < $cases; $casesIndex++) {               

                $firstLine = explode(' ', $inputArray[++$rowIndex]);
                $matSize =  $firstLine[0];            
                $m = $firstLine[1];              
            
                $ops = array();
            
                for ($i = 0; $i < $m; $i++) {                   
                    $ops_item = $inputArray[++$rowIndex];
                    $ops[] = $ops_item;
                }             
            
                $res = array_merge($res, $this->cubeSum($matSize, $ops));           
                
            }
            
            return response()->json([
                'cube_summation' => $res                
            ]);           

        }catch(Exception $e){

            return response()->json([
                'message' => $e->getMessage(),
            ], 400);

        }
    }

  
}   