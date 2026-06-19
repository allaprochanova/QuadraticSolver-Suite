<?php
// quadratic_solver.php - Решатель квадратных уравнений на PHP (CLI + веб)
// CLI: php quadratic_solver.php 1 -5 6
// Веб: откройте как HTML

function solve_quadratic($a, $b, $c) {
    if ($a == 0) {
        if ($b == 0) return ['type' => 'none'];
        return ['type' => 'linear', 'root' => -$c / $b];
    }
    $D = $b*$b - 4*$a*$c;
    if ($D > 0) {
        $sqrtD = sqrt($D);
        return ['type' => 'real', 'D' => $D, 'x1' => (-$b + $sqrtD) / (2*$a), 'x2' => (-$b - $sqrtD) / (2*$a)];
    } elseif ($D == 0) {
        return ['type' => 'double', 'D' => $D, 'x' => -$b / (2*$a)];
    } else {
        $real = -$b / (2*$a);
        $imag = sqrt(-$D) / (2*$a);
        return ['type' => 'complex', 'D' => $D, 'real' => $real, 'imag' => $imag];
    }
}

function format_complex($real, $imag) {
    if ($imag >= 0) return number_format($real, 4) . " + " . number_format($imag, 4) . "i";
    else return number_format($real, 4) . " - " . number_format(abs($imag), 4) . "i";
}

if (php_sapi_name() === 'cli') {
    // CLI режим
    $args = $_SERVER['argv'];
    if (count($args) == 4) {
        $a = (float)$args[1];
        $b = (float)$args[2];
        $c = (float)$args[3];
    } else {
        echo "Введите коэффициент a: ";
        $a = (float)trim(fgets(STDIN));
        echo "Введите коэффициент b: ";
        $b = (float)trim(fgets(STDIN));
        echo "Введите коэффициент c: ";
        $c = (float)trim(fgets(STDIN));
    }
    echo "\nУравнение: $a" . "x² + $b" . "x + $c = 0\n";
    $sol = solve_quadratic($a, $b, $c);
    switch ($sol['type']) {
        case 'none':
            if ($c == 0) echo "Бесконечное множество решений (0 = 0).\n";
            else echo "Нет решений (противоречие).\n";
            break;
        case 'linear':
            echo "Линейное уравнение, корень: " . number_format($sol['root'], 4) . "\n";
            break;
        case 'real':
            echo "Дискриминант D = " . number_format($sol['D'], 4) . "\n";
            echo "Корни:\nx₁ = " . number_format($sol['x1'], 4) . "\n";
            echo "x₂ = " . number_format($sol['x2'], 4) . "\n";
            break;
        case 'double':
            echo "Дискриминант D = " . number_format($sol['D'], 4) . "\n";
            echo "Корень (двойной): x = " . number_format($sol['x'], 4) . "\n";
            break;
        case 'complex':
            echo "Дискриминант D = " . number_format($sol['D'], 4) . "\n";
            echo "Комплексные корни:\n";
            echo "x₁ = " . format_complex($sol['real'], $sol['imag']) . "\n";
            echo "x₂ = " . format_complex($sol['real'], -$sol['imag']) . "\n";
            break;
    }
    exit;
}

// ========== ВЕБ-ИНТЕРФЕЙС ==========
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📐 Решатель квадратных уравнений (PHP)</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7fb; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 100px; }
        input { padding: 6px; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #3498db; color: white; border: none; cursor: pointer; padding: 6px 20px; border-radius: 4px; }
        button:hover { background: #2980b9; }
        .result { background: #ecf0f1; padding: 15px; border-radius: 8px; margin-top: 20px; white-space: pre-wrap; font-family: monospace; }
    </style>
</head>
<body>
<div class="container">
    <h1>📐 Решатель квадратных уравнений (PHP)</h1>
    <form method="GET">
        <div class="form-group">
            <label>Коэффициент a:</label>
            <input type="number" step="any" name="a" value="<?= isset($_GET['a']) ? htmlspecialchars($_GET['a']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Коэффициент b:</label>
            <input type="number" step="any" name="b" value="<?= isset($_GET['b']) ? htmlspecialchars($_GET['b']) : '' ?>" required>
        </div>
        <div class="form-group">
            <label>Коэффициент c:</label>
            <input type="number" step="any" name="c" value="<?= isset($_GET['c']) ? htmlspecialchars($_GET['c']) : '' ?>" required>
        </div>
        <button type="submit">Решить</button>
    </form>

    <?php if (isset($_GET['a']) && isset($_GET['b']) && isset($_GET['c'])): 
        $a = (float)$_GET['a'];
        $b = (float)$_GET['b'];
        $c = (float)$_GET['c'];
        $sol = solve_quadratic($a, $b, $c);
        $output = "Уравнение: " . $a . "x² + " . $b . "x + " . $c . " = 0\n";
        switch ($sol['type']) {
            case 'none':
                if ($c == 0) $output .= "Бесконечное множество решений (0 = 0).";
                else $output .= "Нет решений (противоречие).";
                break;
            case 'linear':
                $output .= "Линейное уравнение, корень: " . number_format($sol['root'], 4);
                break;
            case 'real':
                $output .= "Дискриминант D = " . number_format($sol['D'], 4) . "\n";
                $output .= "Корни:\nx₁ = " . number_format($sol['x1'], 4) . "\n";
                $output .= "x₂ = " . number_format($sol['x2'], 4);
                break;
            case 'double':
                $output .= "Дискриминант D = " . number_format($sol['D'], 4) . "\n";
                $output .= "Корень (двойной): x = " . number_format($sol['x'], 4);
                break;
            case 'complex':
                $output .= "Дискриминант D = " . number_format($sol['D'], 4) . "\n";
                $output .= "Комплексные корни:\n";
                $output .= "x₁ = " . format_complex($sol['real'], $sol['imag']) . "\n";
                $output .= "x₂ = " . format_complex($sol['real'], -$sol['imag']);
                break;
        }
    ?>
        <div class="result"><?= htmlspecialchars($output) ?></div>
    <?php endif; ?>
</div>
</body>
</html>
