<?php

use sqonk\phext\plotlib\BulkPlot;

function multiLines(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
     
    $plot->add('line', [$l1, $l2], [
        'title' => 'lines',
        'xseries' => range(1, 10),
        'xformatter' => function ($v) {
            return "Pt $v";
        }
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile); 
    
    return $img;
}

function lineFills(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
     
    $plot->add('linefill', [$l2, $l1], [
        'title' => 'line fills',
        'xseries' => range(1, 10),
        'xformatter' => function ($v) {
            return "Pt $v";
        },
        'labelangle' => 90,
        'xtitle' => 'Points  ',
        'ytitle' => 'Occurances'
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile); 
    
    return $img;
}

function basicScatter(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('scatter', [$l1, $l2], [
        'title' => 'scatter',
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img; 
}

function squareScatter(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('scatter', [$l1, $l2], [
        'title' => 'square scatter',
        'scattershape' => MARK_SQUARE,
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img; 
}

function scatterLine(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('scatter', [$l1, $l2], [
        'title' => 'scatter lines',
        'scatterline' => true
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img; 
}

function scatterImpulse(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    
    $plot->add('scatter', [$l1], [
        'title' => 'scatter impulse',
        'scatterimpulse' => true,
        'scattershape' => MARK_SQUARE
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img; 
}

function bars(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('bar', [$l1, $l2], [
        'title' => 'bars',
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img; 
}

function stackedBarsAndAuxlines(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    $l3 = [2,5,13,12,17,16,7,4,17,17];
    
    $plot->add('barstacked', [$l1, $l2], [
        'title' => 'stack bars',
        'auxlines' => [
            ['values' => $l3, 'legend' => 'auxlines']
        ]
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img; 
}

function backgroundBars(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('line', [$l1], [
        'title' => 'auxbars',
        'xseries' => range(1, 10),
        'xformatter' => function($v) {
            return "Pt $v";
        },
        'yformatter' => function($v) {
            return "Vl $v";
        },
        'bars' => $l2,
        'barWidth' => 10,
        'barColor' => 'gray',
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img;
}

function infiniteLines(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('line', [$l1, $l2], [
        'title' => 'Infinite Lines',
        'xseries' => range(1, 10),
        'lines' => [
            ['direction' => 'h', 'value' => 7, 'color' => 'red'],
            ['direction' => 'v', 'value' => 4, 'color' => 'blue']
        ]
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img;
}

function regions(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [19,3,2,7,9,18,4,15,12,13];
    $l2 = [17,20,19,17,19,5,13,8,15,8];
    
    $plot->add('line', [$l1], [
        'title' => 'Regions',
        'xseries' => range(1, 10),
        'regions' => [
            ['x' => 3, 'y' => 20, 'x2' => 7, 'y2' => 15, 'color' => 'red@0.3'],
            ['x' => 6, 'y' => 2, 'x2' => 10, 'y2' => 0, 'color' => 'red@0.3']
        ]
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img;
}

function stockplot(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [3,6,2,6, 6,4,3,6, 4,6,1,8, 3,2,1,7, 2,6,2,9];
    $plot->add('stock', [$l1], [
        'title' => 'Candlesticks',
        'xseries' => range(1, 5),
        'margin' => [55,55,55,55],
        'configCallback' => function($chart) {
            $chart->yscale->ticks->SupressZeroLabel(false);
            $chart->xscale->ticks->SupressZeroLabel(false);
            $chart->SetClipping(false);
        }
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img;
}

function boxplot(bool $writeToFile = false): string
{
    $plot = new BulkPlot;
    $plot->output_path('./');
    
    $l1 = [3,6,2,6,4, 6,4,3,6,4, 4,6,1,8,5, 3,2,1,7,4, 2,6,2,9,4];
    $plot->add('box', [$l1], [
        'title' => 'box',
        'xseries' => range(1, 5),
        'margin' => [55,55,55,55],
        'configCallback' => function($chart) {
            $chart->yscale->ticks->SupressZeroLabel(false);
            $chart->xscale->ticks->SupressZeroLabel(false);
            $chart->SetClipping(false);
        }
    ]);
    
    [$img] = $plot->render(700, 500, $writeToFile);
    
    return $img;
}
