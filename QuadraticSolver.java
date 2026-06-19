// QuadraticSolver.java - Решатель квадратных уравнений на Java (CLI)
import java.util.Scanner;

public class QuadraticSolver {

    static class Solution {
        String type; // "linear", "real", "double", "complex", "none"
        double D;
        double x1, x2;
        double real, imag;
        double root;

        Solution(String type) { this.type = type; }
    }

    public static Solution solve(double a, double b, double c) {
        if (a == 0) {
            if (b == 0) {
                return new Solution("none");
            }
            Solution sol = new Solution("linear");
            sol.root = -c / b;
            return sol;
        }
        double D = b*b - 4*a*c;
        if (D > 0) {
            double sqrtD = Math.sqrt(D);
            Solution sol = new Solution("real");
            sol.D = D;
            sol.x1 = (-b + sqrtD) / (2*a);
            sol.x2 = (-b - sqrtD) / (2*a);
            return sol;
        } else if (D == 0) {
            Solution sol = new Solution("double");
            sol.D = D;
            sol.x1 = -b / (2*a);
            return sol;
        } else {
            Solution sol = new Solution("complex");
            sol.D = D;
            sol.real = -b / (2*a);
            sol.imag = Math.sqrt(-D) / (2*a);
            return sol;
        }
    }

    public static String formatComplex(double real, double imag) {
        if (imag >= 0) return String.format("%.4f + %.4fi", real, imag);
        else return String.format("%.4f - %.4fi", real, -imag);
    }

    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        double a, b, c;
        if (args.length == 3) {
            a = Double.parseDouble(args[0]);
            b = Double.parseDouble(args[1]);
            c = Double.parseDouble(args[2]);
        } else {
            System.out.print("Введите коэффициент a: ");
            a = scanner.nextDouble();
            System.out.print("Введите коэффициент b: ");
            b = scanner.nextDouble();
            System.out.print("Введите коэффициент c: ");
            c = scanner.nextDouble();
        }

        System.out.printf("\nУравнение: %.1fx² + %.1fx + %.1f = 0\n", a, b, c);
        Solution sol = solve(a, b, c);

        switch (sol.type) {
            case "none":
                if (c == 0) System.out.println("Бесконечное множество решений (0 = 0).");
                else System.out.println("Нет решений (противоречие).");
                break;
            case "linear":
                System.out.printf("Линейное уравнение, корень: %.4f\n", sol.root);
                break;
            case "real":
                System.out.printf("Дискриминант D = %.4f\n", sol.D);
                System.out.printf("Корни:\nx₁ = %.4f\nx₂ = %.4f\n", sol.x1, sol.x2);
                break;
            case "double":
                System.out.printf("Дискриминант D = %.4f\n", sol.D);
                System.out.printf("Корень (двойной): x = %.4f\n", sol.x1);
                break;
            case "complex":
                System.out.printf("Дискриминант D = %.4f\n", sol.D);
                System.out.println("Комплексные корни:");
                System.out.printf("x₁ = %s\n", formatComplex(sol.real, sol.imag));
                System.out.printf("x₂ = %s\n", formatComplex(sol.real, -sol.imag));
                break;
        }
        scanner.close();
    }
}
