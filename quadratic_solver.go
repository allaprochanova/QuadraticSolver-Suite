// quadratic_solver.go - Решатель квадратных уравнений на Go (CLI)
package main

import (
	"bufio"
	"flag"
	"fmt"
	"math"
	"os"
	"strconv"
	"strings"
)

type Result struct {
	Type   string  // "real", "double", "complex", "linear", "none"
	D      float64
	X1     float64
	X2     float64
	Real   float64
	Imag   float64
	Root   float64
}

func solveQuadratic(a, b, c float64) Result {
	if a == 0 {
		if b == 0 {
			return Result{Type: "none"}
		}
		return Result{Type: "linear", Root: -c / b}
	}
	D := b*b - 4*a*c
	if D > 0 {
		sqrtD := math.Sqrt(D)
		return Result{Type: "real", D: D, X1: (-b + sqrtD) / (2 * a), X2: (-b - sqrtD) / (2 * a)}
	} else if D == 0 {
		return Result{Type: "double", D: D, X1: -b / (2 * a)}
	} else {
		real := -b / (2 * a)
		imag := math.Sqrt(-D) / (2 * a)
		return Result{Type: "complex", D: D, Real: real, Imag: imag}
	}
}

func formatComplex(real, imag float64) string {
	if imag >= 0 {
		return fmt.Sprintf("%.4f + %.4fi", real, imag)
	} else {
		return fmt.Sprintf("%.4f - %.4fi", real, -imag)
	}
}

func main() {
	var aFlag, bFlag, cFlag float64
	flag.Float64Var(&aFlag, "a", 0, "Коэффициент a")
	flag.Float64Var(&bFlag, "b", 0, "Коэффициент b")
	flag.Float64Var(&cFlag, "c", 0, "Коэффициент c")
	flag.Parse()

	var a, b, c float64
	if flag.NFlag() == 3 {
		a, b, c = aFlag, bFlag, cFlag
	} else {
		reader := bufio.NewReader(os.Stdin)
		fmt.Print("Введите коэффициент a: ")
		input, _ := reader.ReadString('\n')
		a, _ = strconv.ParseFloat(strings.TrimSpace(input), 64)
		fmt.Print("Введите коэффициент b: ")
		input, _ = reader.ReadString('\n')
		b, _ = strconv.ParseFloat(strings.TrimSpace(input), 64)
		fmt.Print("Введите коэффициент c: ")
		input, _ = reader.ReadString('\n')
		c, _ = strconv.ParseFloat(strings.TrimSpace(input), 64)
	}

	fmt.Printf("\nУравнение: %.1fx² + %.1fx + %.1f = 0\n", a, b, c)
	res := solveQuadratic(a, b, c)

	switch res.Type {
	case "none":
		if c == 0 {
			fmt.Println("Бесконечное множество решений (0 = 0).")
		} else {
			fmt.Println("Нет решений (противоречие).")
		}
	case "linear":
		fmt.Printf("Линейное уравнение, корень: %.4f\n", res.Root)
	case "real":
		fmt.Printf("Дискриминант D = %.4f\n", res.D)
		fmt.Printf("Корни:\nx₁ = %.4f\nx₂ = %.4f\n", res.X1, res.X2)
	case "double":
		fmt.Printf("Дискриминант D = %.4f\n", res.D)
		fmt.Printf("Корень (двойной): x = %.4f\n", res.X1)
	case "complex":
		fmt.Printf("Дискриминант D = %.4f\n", res.D)
		fmt.Println("Комплексные корни:")
		fmt.Printf("x₁ = %s\n", formatComplex(res.Real, res.Imag))
		fmt.Printf("x₂ = %s\n", formatComplex(res.Real, -res.Imag))
	}
}
