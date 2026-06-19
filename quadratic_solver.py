#!/usr/bin/env python3
"""
quadratic_solver.py - Решатель квадратных уравнений на Python
Поддерживает: действительные и комплексные корни, ввод через аргументы или интерактивно.
"""
import sys
import math
import argparse

def solve_quadratic(a, b, c):
    """Возвращает кортеж (discriminant, корень1, корень2)"""
    if a == 0:
        # линейное уравнение bx + c = 0
        if b == 0:
            return None, None, None  # нет решения или бесконечно
        root = -c / b
        return None, root, None
    D = b*b - 4*a*c
    if D > 0:
        sqrt_D = math.sqrt(D)
        x1 = (-b + sqrt_D) / (2*a)
        x2 = (-b - sqrt_D) / (2*a)
        return D, x1, x2
    elif D == 0:
        x = -b / (2*a)
        return D, x, x
    else:
        # Комплексные корни
        real = -b / (2*a)
        imag = math.sqrt(-D) / (2*a)
        return D, (real, imag), (real, -imag)  # возвращаем кортежи для комплексных

def format_complex(z):
    real, imag = z
    if imag >= 0:
        return f"{real:.4f} + {imag:.4f}i"
    else:
        return f"{real:.4f} - {abs(imag):.4f}i"

def main():
    parser = argparse.ArgumentParser(description="Решатель квадратных уравнений")
    parser.add_argument("-a", type=float, help="Коэффициент a")
    parser.add_argument("-b", type=float, help="Коэффициент b")
    parser.add_argument("-c", type=float, help="Коэффициент c")
    args = parser.parse_args()

    if args.a is not None and args.b is not None and args.c is not None:
        a, b, c = args.a, args.b, args.c
    else:
        try:
            a = float(input("Введите коэффициент a: "))
            b = float(input("Введите коэффициент b: "))
            c = float(input("Введите коэффициент c: "))
        except ValueError:
            print("Ошибка: введите числа.")
            return

    print(f"\nУравнение: {a}x² + {b}x + {c} = 0")
    if a == 0:
        if b == 0:
            if c == 0:
                print("Бесконечное множество решений (0 = 0).")
            else:
                print("Нет решений (противоречие).")
        else:
            root = -c / b
            print(f"Линейное уравнение, корень: {root:.4f}")
        return

    D, r1, r2 = solve_quadratic(a, b, c)
    if D is None:
        return  # уже обработано выше

    print(f"Дискриминант D = {D:.4f}")
    if D > 0:
        print(f"Корни:\nx₁ = {r1:.4f}\nx₂ = {r2:.4f}")
    elif D == 0:
        print(f"Корень (двойной): x = {r1:.4f}")
    else:
        print(f"Комплексные корни:")
        print(f"x₁ = {format_complex(r1)}")
        print(f"x₂ = {format_complex(r2)}")

if __name__ == "__main__":
    main()
