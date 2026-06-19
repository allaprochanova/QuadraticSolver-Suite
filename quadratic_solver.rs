// quadratic_solver.rs - Решатель квадратных уравнений на Rust (CLI)
use std::io;
use std::io::Write;
use std::f64;

#[derive(Debug)]
enum Solution {
    None,
    Linear(f64),
    Real { d: f64, x1: f64, x2: f64 },
    Double { d: f64, x: f64 },
    Complex { d: f64, real: f64, imag: f64 },
}

fn solve_quadratic(a: f64, b: f64, c: f64) -> Solution {
    if a == 0.0 {
        if b == 0.0 {
            return Solution::None;
        }
        return Solution::Linear(-c / b);
    }
    let d = b * b - 4.0 * a * c;
    if d > 0.0 {
        let sqrt_d = d.sqrt();
        let x1 = (-b + sqrt_d) / (2.0 * a);
        let x2 = (-b - sqrt_d) / (2.0 * a);
        Solution::Real { d, x1, x2 }
    } else if d == 0.0 {
        let x = -b / (2.0 * a);
        Solution::Double { d, x }
    } else {
        let real = -b / (2.0 * a);
        let imag = (-d).sqrt() / (2.0 * a);
        Solution::Complex { d, real, imag }
    }
}

fn format_complex(real: f64, imag: f64) -> String {
    if imag >= 0.0 {
        format!("{:.4} + {:.4}i", real, imag)
    } else {
        format!("{:.4} - {:.4}i", real, -imag)
    }
}

fn read_float(prompt: &str) -> f64 {
    print!("{}", prompt);
    io::stdout().flush().unwrap();
    let mut input = String::new();
    io::stdin().read_line(&mut input).expect("Ошибка ввода");
    input.trim().parse().expect("Введите число")
}

fn main() {
    let args: Vec<String> = std::env::args().collect();
    let (a, b, c) = if args.len() == 4 {
        let a = args[1].parse().expect("Неверный a");
        let b = args[2].parse().expect("Неверный b");
        let c = args[3].parse().expect("Неверный c");
        (a, b, c)
    } else {
        println!("Решатель квадратных уравнений");
        let a = read_float("Введите коэффициент a: ");
        let b = read_float("Введите коэффициент b: ");
        let c = read_float("Введите коэффициент c: ");
        (a, b, c)
    };

    println!("\nУравнение: {:.1}x² + {:.1}x + {:.1} = 0", a, b, c);
    let solution = solve_quadratic(a, b, c);

    match solution {
        Solution::None => {
            if c == 0.0 {
                println!("Бесконечное множество решений (0 = 0).");
            } else {
                println!("Нет решений (противоречие).");
            }
        }
        Solution::Linear(root) => {
            println!("Линейное уравнение, корень: {:.4}", root);
        }
        Solution::Real { d, x1, x2 } => {
            println!("Дискриминант D = {:.4}", d);
            println!("Корни:\nx₁ = {:.4}\nx₂ = {:.4}", x1, x2);
        }
        Solution::Double { d, x } => {
            println!("Дискриминант D = {:.4}", d);
            println!("Корень (двойной): x = {:.4}", x);
        }
        Solution::Complex { d, real, imag } => {
            println!("Дискриминант D = {:.4}", d);
            println!("Комплексные корни:");
            println!("x₁ = {}", format_complex(real, imag));
            println!("x₂ = {}", format_complex(real, -imag));
        }
    }
}
