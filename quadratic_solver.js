#!/usr/bin/env node
/**
 * quadratic_solver.js - Решатель квадратных уравнений на JavaScript (Node.js)
 */
const readline = require('readline');
const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout
});

function parseArgs() {
    const args = process.argv.slice(2);
    let a, b, c;
    for (let i = 0; i < args.length; i++) {
        if (args[i] === '-a') a = parseFloat(args[++i]);
        else if (args[i] === '-b') b = parseFloat(args[++i]);
        else if (args[i] === '-c') c = parseFloat(args[++i]);
    }
    return { a, b, c };
}

function solveQuadratic(a, b, c) {
    if (a === 0) {
        if (b === 0) return { type: 'none' };
        return { type: 'linear', root: -c / b };
    }
    const D = b*b - 4*a*c;
    if (D > 0) {
        const sqrtD = Math.sqrt(D);
        return { type: 'real', D, x1: (-b + sqrtD) / (2*a), x2: (-b - sqrtD) / (2*a) };
    } else if (D === 0) {
        return { type: 'double', D, x: -b / (2*a) };
    } else {
        const real = -b / (2*a);
        const imag = Math.sqrt(-D) / (2*a);
        return { type: 'complex', D, real, imag };
    }
}

function formatComplex(real, imag) {
    if (imag >= 0) return `${real.toFixed(4)} + ${imag.toFixed(4)}i`;
    else return `${real.toFixed(4)} - ${Math.abs(imag).toFixed(4)}i`;
}

function prompt(question) {
    return new Promise(resolve => rl.question(question, resolve));
}

async function main() {
    const args = parseArgs();
    let a, b, c;
    if (args.a !== undefined && args.b !== undefined && args.c !== undefined) {
        a = args.a; b = args.b; c = args.c;
    } else {
        try {
            a = parseFloat(await prompt('Введите коэффициент a: '));
            b = parseFloat(await prompt('Введите коэффициент b: '));
            c = parseFloat(await prompt('Введите коэффициент c: '));
        } catch (e) {
            console.log('Ошибка ввода.');
            rl.close();
            return;
        }
    }

    console.log(`\nУравнение: ${a}x² + ${b}x + ${c} = 0`);
    const result = solveQuadratic(a, b, c);

    if (result.type === 'none') {
        if (c === 0) console.log('Бесконечное множество решений (0 = 0).');
        else console.log('Нет решений (противоречие).');
    } else if (result.type === 'linear') {
        console.log(`Линейное уравнение, корень: ${result.root.toFixed(4)}`);
    } else if (result.type === 'real') {
        console.log(`Дискриминант D = ${result.D.toFixed(4)}`);
        console.log(`Корни:\nx₁ = ${result.x1.toFixed(4)}\nx₂ = ${result.x2.toFixed(4)}`);
    } else if (result.type === 'double') {
        console.log(`Дискриминант D = ${result.D.toFixed(4)}`);
        console.log(`Корень (двойной): x = ${result.x.toFixed(4)}`);
    } else if (result.type === 'complex') {
        console.log(`Дискриминант D = ${result.D.toFixed(4)}`);
        console.log(`Комплексные корни:`);
        console.log(`x₁ = ${formatComplex(result.real, result.imag)}`);
        console.log(`x₂ = ${formatComplex(result.real, -result.imag)}`);
    }
    rl.close();
}

main().catch(console.error);
