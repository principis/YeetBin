/*!
 * This file is part of YeetBin.
 * Copyright (C) 2023 Arthur Bols
 *
 * YeetBin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * YeetBin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with YeetBin.  If not, see <https://www.gnu.org/licenses/>.
 */

import hljs from 'highlight.js/lib/core';

async function highlight() {
    const language = document.getElementById('paste-content').dataset.codeLanguage;

    if (language === 'autodetect') {
        return autodetect();
    }

    let prom;

    switch (language) {
        case '1c':
            prom = import('highlight.js/lib/languages/1c');
            break;
        case 'abnf':
            prom = import('highlight.js/lib/languages/abnf');
            break;
        case 'accesslog':
            prom = import('highlight.js/lib/languages/accesslog');
            break;
        case 'actionscript':
            prom = import('highlight.js/lib/languages/actionscript');
            break;
        case 'ada':
            prom = import('highlight.js/lib/languages/ada');
            break;
        case 'angelscript':
            prom = import('highlight.js/lib/languages/angelscript');
            break;
        case 'apache':
            prom = import('highlight.js/lib/languages/apache');
            break;
        case 'applescript':
            prom = import('highlight.js/lib/languages/applescript');
            break;
        case 'arcade':
            prom = import('highlight.js/lib/languages/arcade');
            break;
        case 'arduino':
            prom = import('highlight.js/lib/languages/arduino');
            break;
        case 'armasm':
            prom = import('highlight.js/lib/languages/armasm');
            break;
        case 'asciidoc':
            prom = import('highlight.js/lib/languages/asciidoc');
            break;
        case 'aspectj':
            prom = import('highlight.js/lib/languages/aspectj');
            break;
        case 'autohotkey':
            prom = import('highlight.js/lib/languages/autohotkey');
            break;
        case 'autoit':
            prom = import('highlight.js/lib/languages/autoit');
            break;
        case 'avrasm':
            prom = import('highlight.js/lib/languages/avrasm');
            break;
        case 'awk':
            prom = import('highlight.js/lib/languages/awk');
            break;
        case 'axapta':
            prom = import('highlight.js/lib/languages/axapta');
            break;
        case 'bash':
            prom = import('highlight.js/lib/languages/bash');
            break;
        case 'basic':
            prom = import('highlight.js/lib/languages/basic');
            break;
        case 'bnf':
            prom = import('highlight.js/lib/languages/bnf');
            break;
        case 'brainfuck':
            prom = import('highlight.js/lib/languages/brainfuck');
            break;
        case 'c':
            prom = import('highlight.js/lib/languages/c');
            break;
        case 'cal':
            prom = import('highlight.js/lib/languages/cal');
            break;
        case 'capnproto':
            prom = import('highlight.js/lib/languages/capnproto');
            break;
        case 'ceylon':
            prom = import('highlight.js/lib/languages/ceylon');
            break;
        case 'clean':
            prom = import('highlight.js/lib/languages/clean');
            break;
        case 'clojure-repl':
            prom = import('highlight.js/lib/languages/clojure-repl');
            break;
        case 'clojure':
            prom = import('highlight.js/lib/languages/clojure');
            break;
        case 'cmake':
            prom = import('highlight.js/lib/languages/cmake');
            break;
        case 'coffeescript':
            prom = import('highlight.js/lib/languages/coffeescript');
            break;
        case 'coq':
            prom = import('highlight.js/lib/languages/coq');
            break;
        case 'cos':
            prom = import('highlight.js/lib/languages/cos');
            break;
        case 'cpp':
            prom = import('highlight.js/lib/languages/cpp');
            break;
        case 'crmsh':
            prom = import('highlight.js/lib/languages/crmsh');
            break;
        case 'crystal':
            prom = import('highlight.js/lib/languages/crystal');
            break;
        case 'csharp':
            prom = import('highlight.js/lib/languages/csharp');
            break;
        case 'csp':
            prom = import('highlight.js/lib/languages/csp');
            break;
        case 'css':
            prom = import('highlight.js/lib/languages/css');
            break;
        case 'd':
            prom = import('highlight.js/lib/languages/d');
            break;
        case 'dart':
            prom = import('highlight.js/lib/languages/dart');
            break;
        case 'delphi':
            prom = import('highlight.js/lib/languages/delphi');
            break;
        case 'diff':
            prom = import('highlight.js/lib/languages/diff');
            break;
        case 'django':
            prom = import('highlight.js/lib/languages/django');
            break;
        case 'dns':
            prom = import('highlight.js/lib/languages/dns');
            break;
        case 'dockerfile':
            prom = import('highlight.js/lib/languages/dockerfile');
            break;
        case 'dos':
            prom = import('highlight.js/lib/languages/dos');
            break;
        case 'dsconfig':
            prom = import('highlight.js/lib/languages/dsconfig');
            break;
        case 'dts':
            prom = import('highlight.js/lib/languages/dts');
            break;
        case 'dust':
            prom = import('highlight.js/lib/languages/dust');
            break;
        case 'ebnf':
            prom = import('highlight.js/lib/languages/ebnf');
            break;
        case 'elixir':
            prom = import('highlight.js/lib/languages/elixir');
            break;
        case 'elm':
            prom = import('highlight.js/lib/languages/elm');
            break;
        case 'erb':
            prom = import('highlight.js/lib/languages/erb');
            break;
        case 'erlang-repl':
            prom = import('highlight.js/lib/languages/erlang-repl');
            break;
        case 'erlang':
            prom = import('highlight.js/lib/languages/erlang');
            break;
        case 'excel':
            prom = import('highlight.js/lib/languages/excel');
            break;
        case 'fix':
            prom = import('highlight.js/lib/languages/fix');
            break;
        case 'flix':
            prom = import('highlight.js/lib/languages/flix');
            break;
        case 'fortran':
            prom = import('highlight.js/lib/languages/fortran');
            break;
        case 'fsharp':
            prom = import('highlight.js/lib/languages/fsharp');
            break;
        case 'gams':
            prom = import('highlight.js/lib/languages/gams');
            break;
        case 'gauss':
            prom = import('highlight.js/lib/languages/gauss');
            break;
        case 'gcode':
            prom = import('highlight.js/lib/languages/gcode');
            break;
        case 'gherkin':
            prom = import('highlight.js/lib/languages/gherkin');
            break;
        case 'glsl':
            prom = import('highlight.js/lib/languages/glsl');
            break;
        case 'gml':
            prom = import('highlight.js/lib/languages/gml');
            break;
        case 'go':
            prom = import('highlight.js/lib/languages/go');
            break;
        case 'golo':
            prom = import('highlight.js/lib/languages/golo');
            break;
        case 'gradle':
            prom = import('highlight.js/lib/languages/gradle');
            break;
        case 'graphql':
            prom = import('highlight.js/lib/languages/graphql');
            break;
        case 'groovy':
            prom = import('highlight.js/lib/languages/groovy');
            break;
        case 'haml':
            prom = import('highlight.js/lib/languages/haml');
            break;
        case 'handlebars':
            prom = import('highlight.js/lib/languages/handlebars');
            break;
        case 'haskell':
            prom = import('highlight.js/lib/languages/haskell');
            break;
        case 'haxe':
            prom = import('highlight.js/lib/languages/haxe');
            break;
        case 'hsp':
            prom = import('highlight.js/lib/languages/hsp');
            break;
        case 'http':
            prom = import('highlight.js/lib/languages/http');
            break;
        case 'hy':
            prom = import('highlight.js/lib/languages/hy');
            break;
        case 'inform7':
            prom = import('highlight.js/lib/languages/inform7');
            break;
        case 'ini':
            prom = import('highlight.js/lib/languages/ini');
            break;
        case 'irpf90':
            prom = import('highlight.js/lib/languages/irpf90');
            break;
        case 'isbl':
            prom = import('highlight.js/lib/languages/isbl');
            break;
        case 'java':
            prom = import('highlight.js/lib/languages/java');
            break;
        case 'javascript':
            prom = import('highlight.js/lib/languages/javascript');
            break;
        case 'jboss-cli':
            prom = import('highlight.js/lib/languages/jboss-cli');
            break;
        case 'json':
            prom = import('highlight.js/lib/languages/json');
            break;
        case 'julia-repl':
            prom = import('highlight.js/lib/languages/julia-repl');
            break;
        case 'julia':
            prom = import('highlight.js/lib/languages/julia');
            break;
        case 'kotlin':
            prom = import('highlight.js/lib/languages/kotlin');
            break;
        case 'lasso':
            prom = import('highlight.js/lib/languages/lasso');
            break;
        case 'latex':
            prom = import('highlight.js/lib/languages/latex');
            break;
        case 'ldif':
            prom = import('highlight.js/lib/languages/ldif');
            break;
        case 'leaf':
            prom = import('highlight.js/lib/languages/leaf');
            break;
        case 'less':
            prom = import('highlight.js/lib/languages/less');
            break;
        case 'lisp':
            prom = import('highlight.js/lib/languages/lisp');
            break;
        case 'livecodeserver':
            prom = import('highlight.js/lib/languages/livecodeserver');
            break;
        case 'livescript':
            prom = import('highlight.js/lib/languages/livescript');
            break;
        case 'llvm':
            prom = import('highlight.js/lib/languages/llvm');
            break;
        case 'lsl':
            prom = import('highlight.js/lib/languages/lsl');
            break;
        case 'lua':
            prom = import('highlight.js/lib/languages/lua');
            break;
        case 'makefile':
            prom = import('highlight.js/lib/languages/makefile');
            break;
        case 'markdown':
            prom = import('highlight.js/lib/languages/markdown');
            break;
        case 'mathematica':
            prom = import('highlight.js/lib/languages/mathematica');
            break;
        case 'matlab':
            prom = import('highlight.js/lib/languages/matlab');
            break;
        case 'maxima':
            prom = import('highlight.js/lib/languages/maxima');
            break;
        case 'mel':
            prom = import('highlight.js/lib/languages/mel');
            break;
        case 'mercury':
            prom = import('highlight.js/lib/languages/mercury');
            break;
        case 'mipsasm':
            prom = import('highlight.js/lib/languages/mipsasm');
            break;
        case 'mizar':
            prom = import('highlight.js/lib/languages/mizar');
            break;
        case 'mojolicious':
            prom = import('highlight.js/lib/languages/mojolicious');
            break;
        case 'monkey':
            prom = import('highlight.js/lib/languages/monkey');
            break;
        case 'moonscript':
            prom = import('highlight.js/lib/languages/moonscript');
            break;
        case 'n1ql':
            prom = import('highlight.js/lib/languages/n1ql');
            break;
        case 'nestedtext':
            prom = import('highlight.js/lib/languages/nestedtext');
            break;
        case 'nginx':
            prom = import('highlight.js/lib/languages/nginx');
            break;
        case 'nim':
            prom = import('highlight.js/lib/languages/nim');
            break;
        case 'nix':
            prom = import('highlight.js/lib/languages/nix');
            break;
        case 'node-repl':
            prom = import('highlight.js/lib/languages/node-repl');
            break;
        case 'nsis':
            prom = import('highlight.js/lib/languages/nsis');
            break;
        case 'objectivec':
            prom = import('highlight.js/lib/languages/objectivec');
            break;
        case 'ocaml':
            prom = import('highlight.js/lib/languages/ocaml');
            break;
        case 'openscad':
            prom = import('highlight.js/lib/languages/openscad');
            break;
        case 'oxygene':
            prom = import('highlight.js/lib/languages/oxygene');
            break;
        case 'parser3':
            prom = import('highlight.js/lib/languages/parser3');
            break;
        case 'perl':
            prom = import('highlight.js/lib/languages/perl');
            break;
        case 'pf':
            prom = import('highlight.js/lib/languages/pf');
            break;
        case 'pgsql':
            prom = import('highlight.js/lib/languages/pgsql');
            break;
        case 'php-template':
            prom = import('highlight.js/lib/languages/php-template');
            break;
        case 'php':
            prom = import('highlight.js/lib/languages/php');
            break;
        case 'plaintext':
            prom = import('highlight.js/lib/languages/plaintext');
            break;
        case 'pony':
            prom = import('highlight.js/lib/languages/pony');
            break;
        case 'powershell':
            prom = import('highlight.js/lib/languages/powershell');
            break;
        case 'processing':
            prom = import('highlight.js/lib/languages/processing');
            break;
        case 'profile':
            prom = import('highlight.js/lib/languages/profile');
            break;
        case 'prolog':
            prom = import('highlight.js/lib/languages/prolog');
            break;
        case 'properties':
            prom = import('highlight.js/lib/languages/properties');
            break;
        case 'protobuf':
            prom = import('highlight.js/lib/languages/protobuf');
            break;
        case 'puppet':
            prom = import('highlight.js/lib/languages/puppet');
            break;
        case 'purebasic':
            prom = import('highlight.js/lib/languages/purebasic');
            break;
        case 'python-repl':
            prom = import('highlight.js/lib/languages/python-repl');
            break;
        case 'python':
            prom = import('highlight.js/lib/languages/python');
            break;
        case 'q':
            prom = import('highlight.js/lib/languages/q');
            break;
        case 'qml':
            prom = import('highlight.js/lib/languages/qml');
            break;
        case 'r':
            prom = import('highlight.js/lib/languages/r');
            break;
        case 'reasonml':
            prom = import('highlight.js/lib/languages/reasonml');
            break;
        case 'rib':
            prom = import('highlight.js/lib/languages/rib');
            break;
        case 'roboconf':
            prom = import('highlight.js/lib/languages/roboconf');
            break;
        case 'routeros':
            prom = import('highlight.js/lib/languages/routeros');
            break;
        case 'rsl':
            prom = import('highlight.js/lib/languages/rsl');
            break;
        case 'ruby':
            prom = import('highlight.js/lib/languages/ruby');
            break;
        case 'ruleslanguage':
            prom = import('highlight.js/lib/languages/ruleslanguage');
            break;
        case 'rust':
            prom = import('highlight.js/lib/languages/rust');
            break;
        case 'sas':
            prom = import('highlight.js/lib/languages/sas');
            break;
        case 'scala':
            prom = import('highlight.js/lib/languages/scala');
            break;
        case 'scheme':
            prom = import('highlight.js/lib/languages/scheme');
            break;
        case 'scilab':
            prom = import('highlight.js/lib/languages/scilab');
            break;
        case 'scss':
            prom = import('highlight.js/lib/languages/scss');
            break;
        case 'shell':
            prom = import('highlight.js/lib/languages/shell');
            break;
        case 'smali':
            prom = import('highlight.js/lib/languages/smali');
            break;
        case 'smalltalk':
            prom = import('highlight.js/lib/languages/smalltalk');
            break;
        case 'sml':
            prom = import('highlight.js/lib/languages/sml');
            break;
        case 'sqf':
            prom = import('highlight.js/lib/languages/sqf');
            break;
        case 'sql':
            prom = import('highlight.js/lib/languages/sql');
            break;
        case 'stan':
            prom = import('highlight.js/lib/languages/stan');
            break;
        case 'stata':
            prom = import('highlight.js/lib/languages/stata');
            break;
        case 'step21':
            prom = import('highlight.js/lib/languages/step21');
            break;
        case 'stylus':
            prom = import('highlight.js/lib/languages/stylus');
            break;
        case 'subunit':
            prom = import('highlight.js/lib/languages/subunit');
            break;
        case 'swift':
            prom = import('highlight.js/lib/languages/swift');
            break;
        case 'taggerscript':
            prom = import('highlight.js/lib/languages/taggerscript');
            break;
        case 'tap':
            prom = import('highlight.js/lib/languages/tap');
            break;
        case 'tcl':
            prom = import('highlight.js/lib/languages/tcl');
            break;
        case 'thrift':
            prom = import('highlight.js/lib/languages/thrift');
            break;
        case 'tp':
            prom = import('highlight.js/lib/languages/tp');
            break;
        case 'twig':
            prom = import('highlight.js/lib/languages/twig');
            break;
        case 'typescript':
            prom = import('highlight.js/lib/languages/typescript');
            break;
        case 'vala':
            prom = import('highlight.js/lib/languages/vala');
            break;
        case 'vbnet':
            prom = import('highlight.js/lib/languages/vbnet');
            break;
        case 'vbscript-html':
            prom = import('highlight.js/lib/languages/vbscript-html');
            break;
        case 'vbscript':
            prom = import('highlight.js/lib/languages/vbscript');
            break;
        case 'verilog':
            prom = import('highlight.js/lib/languages/verilog');
            break;
        case 'vhdl':
            prom = import('highlight.js/lib/languages/vhdl');
            break;
        case 'vim':
            prom = import('highlight.js/lib/languages/vim');
            break;
        case 'wasm':
            prom = import('highlight.js/lib/languages/wasm');
            break;
        case 'wren':
            prom = import('highlight.js/lib/languages/wren');
            break;
        case 'x86asm':
            prom = import('highlight.js/lib/languages/x86asm');
            break;
        case 'xl':
            prom = import('highlight.js/lib/languages/xl');
            break;
        case 'xml':
            prom = import('highlight.js/lib/languages/xml');
            break;
        case 'xquery':
            prom = import('highlight.js/lib/languages/xquery');
            break;
        case 'yaml':
            prom = import('highlight.js/lib/languages/yaml');
            break;
        case 'zephir':
            prom = import('highlight.js/lib/languages/zephir');
            break;
    }

    prom.then(({default: lang}) => {
        hljs.registerLanguage(language, lang);
        hljs.highlightAll();
    });
}

async function autodetect() {
    const {default: _hljs} = await import('highlight.js/lib/common');
    _hljs.highlightAll();
}

export {highlight as default};