// QuadraticSolver.cs - Решатель квадратных уравнений на C# (CLI)
using System;

class QuadraticSolver
{
    class Solution
    {
        public string Type; // "linear", "real", "double", "complex", "none"
        public double D;
        public double X1, X2;
        public double Real, Imag;
        public double Root;
    }

    static Solution Solve(double a, double b, double c)
    {
        if (a == 0)
        {
            if (b == 0) return new Solution { Type = "none" };
            return new Solution { Type = "linear", Root = -c / b };
        }
        double D = b * b - 4 * a * c;
        if (D > 0)
        {
            double sqrtD = Math.Sqrt(D);
            return new Solution { Type = "real", D = D, X1 = (-b + sqrtD) / (2 * a), X2 = (-b - sqrtD) / (2 * a) };
        }
        else if (D == 0)
        {
            return new Solution { Type = "double", D = D, X1 = -b / (2 * a) };
        }
        else
        {
            double real = -b / (2 * a);
            double imag = Math.Sqrt(-D) / (2 * a);
            return new Solution { Type = "complex", D = D, Real = real, Imag = imag };
        }
    }

    static string FormatComplex(double real, double imag)
    {
        if (imag >= 0) return $"{real:F4} + {imag:F4}i";
        else return $"{real:F4} - {Math.Abs(imag):F4}i";
    }

    static void Main(string[] args)
    {
        double a, b, c;
        if (args.Length == 3)
        {
            a = double.Parse(args[0]);
            b = double.Parse(args[1]);
            c = double.Parse(args[2]);
        }
        else
        {
            Console.Write("Введите коэффициент a: ");
            a = double.Parse(Console.ReadLine());
            Console.Write("Введите коэффициент b: ");
            b = double.Parse(Console.ReadLine());
            Console.Write("Введите коэффициент c: ");
            c = double.Parse(Console.ReadLine());
        }

        Console.WriteLine($"\nУравнение: {a:F1}x² + {b:F1}x + {c:F1} = 0");
        var sol = Solve(a, b, c);

        switch (sol.Type)
        {
            case "none":
                if (c == 0) Console.WriteLine("Бесконечное множество решений (0 = 0).");
                else Console.WriteLine("Нет решений (противоречие).");
                break;
            case "linear":
                Console.WriteLine($"Линейное уравнение, корень: {sol.Root:F4}");
                break;
            case "real":
                Console.WriteLine($"Дискриминант D = {sol.D:F4}");
                Console.WriteLine($"Корни:\nx₁ = {sol.X1:F4}\nx₂ = {sol.X2:F4}");
                break;
            case "double":
                Console.WriteLine($"Дискриминант D = {sol.D:F4}");
                Console.WriteLine($"Корень (двойной): x = {sol.X1:F4}");
                break;
            case "complex":
                Console.WriteLine($"Дискриминант D = {sol.D:F4}");
                Console.WriteLine("Комплексные корни:");
                Console.WriteLine($"x₁ = {FormatComplex(sol.Real, sol.Imag)}");
                Console.WriteLine($"x₂ = {FormatComplex(sol.Real, -sol.Imag)}");
                break;
        }
    }
}
