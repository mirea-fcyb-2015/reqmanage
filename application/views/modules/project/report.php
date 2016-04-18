<?php 

function show_matrix($matrix) 
{
    $arr = json_decode($matrix, TRUE);
    echo '<table style="border-collapse: collapse;">';
    foreach($arr as $k) {
        echo '<tr>';
        foreach ($k as $a) {
            if(!is_array($a))
                echo '<td style="border: 1px solid #000; padding: 6px 8px;">'. $a .'</td>';
            else
                echo '<td style="border: 1px solid #000; padding: 6px 8px;">'. $a['_data'] .'</td>';
        }
        echo '<tr>';
    }
    echo '</table>';
}

// а это функция для составления отчёта (ох уж эта рекурсия)
function report_recursion($sections)
{
    foreach ($sections as $s) {
        echo '<h3 style="font-size: 18.5px;">1.'. $s['number'] .' '. $s['title'] .'</h2>';
        echo '<p>'. $s['description'] .'</p>';

        if(isset($s['requirements']) && !empty($s['requirements'])) {
            echo '<table style="border-collapse: collapse;"><thead><tr><th style="border: 1px solid #000; padding: 6px 8px;">Название</th>';

            foreach ($s['requirements'][0] as $f => $v) {
                if($f == 'id' || $f == 'title' || $f == 'description')
                    continue;
                echo '<th style="border: 1px solid #000; padding: 8px 15px;">'. transliterate($f, 1) .'</th>';
            }

            echo '</tr></thead><tbody>';

            foreach ($s['requirements'] as $a) {
                echo '<tr>';

                foreach ($a as $f => $v) {
                    if($f == 'id' ||  $f == 'description')
                        continue;

                    echo '<td style="border: 1px solid #000; padding: 6px 8px;">'. $v .'</td>';
                }

                echo '</tr>';
            }
            
            echo '</tbody></table><br>';

            $i = 1;
            foreach ($s['requirements'] as $f) {
                echo '<h3 style="font-size: 16px;">1.'. $s['number'] . $i .'. '. $f['title'] .'</h3>';
                echo '<p>'. $f['description'] .'</p>';
                $i++;
            }
        } // if(isset($s['requirements']))

        if(!empty($s['children']))
            report_recursion($s['children']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Отчет</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="font-family: 'Times New Roman'; font-size: 12px; line-height: 1.5;">

    <h1 style="font-size: 21px;">Введение</h1>
    <?php echo '<p>'. $project->description .'</p>'; ?>
    
    <h1 style="font-size: 21px;">1. Требования к программе или программному изделию</h1>
    <?php 
        report_recursion($sections); 
        if(isset($matrix))
            show_matrix($matrix); 
    ?>

    <h1 style="font-size: 21px;">2. Требования к программной документации</h1>
    <p>Уточняются в процессе разработки.</p>

    <h1 style="font-size: 21px;">3. Технико-экономические показатели</h1>
    <p>Уточняются в процессе разработки.</p>
    <h1 style="font-size: 21px;">4. Стадии и этапы разработки</h1>
    <p>См. план разработки.</p>
    <h1 style="font-size: 21px;">5. Порядок контроля и приемки</h1>
    <p>Испытание системы и контроль качества ее работы провести на базе компьютерного класса кафедры программного обеспечения систем радиоэлектронной аппаратуры. Во время испытаний проверить работу системы по всем требованиям настоящего ТЗ.</p>

</body>
</html>