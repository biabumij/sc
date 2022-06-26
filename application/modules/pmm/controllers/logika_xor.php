        
<?php

        $data_x = array(
            array(1,1),
            array(1,0),
            array(0,1),
            array(0,0));
        $w1=2;
        $w2=-1;
        $w3=-1;
        $w4=2;
        $w5=2;
        $w6=2;

        for ($i=0; $i<4; $i++){

            $z1_in = $data_x[$i][0] * $w1 + $data_x[$i][1] * $w3;
            $z1=0;
            if ($z1_in>=2) {
                $z1=1;
            } else {
                $z1=0;
            };

            $z2_in = $data_x[$i][0] * $w2 + $data_x[$i][1] * $w4;
            $z2 = 0;
            if ($z2_in >=2) {
                $z2 = 1;
            } else {
                $z2 = 0;
            };
            
            $y_in = $z1 * $w5 + $z2 * $w6;
            $y = 0;
            if ($y_in >=2) {
                $y = 1;
            } else {
                $y = 0;
            };

            echo $data_x[$i][0].' '.$data_x[$i][1].' '.$y.'<br />';
        }
