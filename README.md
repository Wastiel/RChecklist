# 📘 Guia do Projeto: Checklist de Tarefas (RChecklist)

> Material de apoio para quem está tendo o **primeiro contato com um CRUD em PHP**. Não precisa entender tudo de uma vez. Leia na ordem, abra os arquivos do projeto ao lado e vá conferindo.

---

## 📑 Índice

1. [O que é este projeto?](#1-o-que-é-este-projeto)
2. [Como uma aplicação WEB funciona (a base de tudo)](#2-como-uma-aplicação-web-funciona-a-base-de-tudo)
3. [As tecnologias usadas](#3-as-tecnologias-usadas)
4. [O Banco de Dados](#4-o-banco-de-dados)
5. [Estrutura de pastas](#5-estrutura-de-pastas)
6. [A pasta `includes/`: peças reaproveitadas](#6-a-pasta-includes-peças-reaproveitadas)
7. [O padrão CRUD: o coração do sistema](#7-o-padrão-crud-o-coração-do-sistema)
8. [Estudo de caso: o módulo Checklist, arquivo por arquivo](#8-estudo-de-caso-o-módulo-checklist-arquivo-por-arquivo)
9. [Modais do Bootstrap](#9-modais-do-bootstrap)
10. [O pequeno JavaScript do projeto](#10-o-pequeno-javascript-do-projeto)
11. [O visual: Bootstrap e CSS](#11-o-visual-bootstrap-e-css)
12. [Como rodar o projeto](#12-como-rodar-o-projeto)
13. [⚠️ Pontos de atenção (o que melhorar)](#13-️-pontos-de-atenção-o-que-melhorar)
14. [Exercícios para praticar](#14-exercícios-para-praticar)
15. [Glossário](#15-glossário)

---

## 1. O que é este projeto?

O **RChecklist** é uma lista de tarefas (*to-do list*) bem simples. Ele foi feito para ensinar o **CRUD**, as quatro operações básicas de quase todo sistema, usando o mesmo padrão de código e de visual do projeto **RAtendimento2**.

Ele responde a perguntas como:

- **O que** preciso fazer? → *Tarefa*
- **Do que** se trata? → *Descrição*
- **Já fiz?** → *Concluída ou Pendente*

### O que o sistema faz

| Funcionalidade        | Descrição                                                             |
|-----------------------|-----------------------------------------------------------------------|
| ➕ Nova tarefa         | Abre uma **modal** com o formulário e cadastra a tarefa               |
| 📋 Listar tarefas      | Mostra todas as tarefas em uma tabela                                 |
| ☑️ Concluir            | Um **checkbox** marca a tarefa como feita (o título fica **riscado**) |
| 📝 Ver descrição       | Clicar no título abre uma **modal** com a descrição                   |
| ✏️ Editar              | Abre a **mesma modal** do cadastro, já preenchida                     |
| 🗑️ Excluir            | Apaga a tarefa de verdade, depois de pedir confirmação                |

> 💡 Diferente do RAtendimento2, aqui **não há login**. Assim o foco fica todo no CRUD.

---

## 2. Como uma aplicação WEB funciona (a base de tudo)

Antes do código, vamos entender a **conversa** entre o navegador e o servidor.

```
┌──────────────┐      1. Pedido (Request)       ┌──────────────────┐
│  NAVEGADOR   │  ───────────────────────────▶  │     SERVIDOR     │
│  (Chrome)    │                                │  (Apache + PHP)  │
│  o "cliente" │  ◀───────────────────────────  │                  │
└──────────────┘      4. Resposta (HTML)        └────────┬─────────┘
                                                         │ 2. PHP executa
                                                         │    e consulta
                                                         ▼
                                                ┌──────────────────┐
                                                │  BANCO DE DADOS  │
                                                │    (MariaDB)     │
                                                └──────────────────┘
                                                  3. devolve os dados
```

### Passo a passo

1. Você digita um endereço ou clica em um link. O navegador faz um **pedido** (*request*) ao servidor.
2. O servidor encontra o arquivo `.php` e **executa o código PHP**.
3. Se precisar de dados, o PHP **pergunta ao banco de dados**.
4. O PHP monta uma página **HTML** e a devolve. O navegador só recebe HTML pronto. **Ele nunca vê o seu código PHP.**

> 💡 **Ideia-chave:** PHP roda no **servidor**. HTML, CSS e JavaScript rodam no **navegador**.

### GET e POST: duas formas de enviar informação

|                    | GET                                | POST                              |
|--------------------|------------------------------------|-----------------------------------|
| Onde vão os dados? | Na **URL** (`toggleConcluido.php?id=5`) | **Escondidos** no corpo do pedido |
| Quando usar?       | Para **buscar/abrir** algo         | Para **enviar formulários** e **alterar dados** |
| Neste projeto      | O checkbox de concluir             | Salvar e Excluir                  |
| Em PHP             | `$_GET['id']`                      | `$_POST['titulo']`                |

---

## 3. As tecnologias usadas

| Tecnologia          | Papel                            | Analogia                                       |
|---------------------|----------------------------------|------------------------------------------------|
| **HTML**            | Estrutura da página              | O **esqueleto** do prédio                      |
| **CSS**             | Aparência (cores, espaços)       | A **pintura e decoração**                      |
| **Bootstrap 5**     | Biblioteca de CSS pronta         | **Móveis prontos**, em vez de fabricar cada um |
| **Bootstrap Icons** | Ícones (lápis, lixeira, check)   | Adesivos prontos                               |
| **JavaScript**      | Comportamento no navegador       | A **eletricidade**: faz as coisas reagirem     |
| **PHP**             | Lógica no servidor               | O **cérebro**                                  |
| **PDO**             | Forma de o PHP falar com o banco | O **telefone** entre PHP e banco               |
| **MariaDB (MySQL)** | Guarda os dados                  | O **arquivo/armário** de dados                 |
| **SQL**             | Linguagem para consultar o banco | O **idioma** que o banco entende               |

> O projeto usa **PHP puro**, sem framework (como Laravel). Isso é ótimo para aprender, pois dá para ver tudo o que está acontecendo.

---

## 4. O Banco de Dados

O banco se chama **`RChecklist`** e tem **uma única tabela**.

### 4.1 A tabela

```
┌──────────────────────────────────┐
│            checklist             │
├──────────────────────────────────┤
│ id_checklist  INT          PK    │
│ titulo        VARCHAR(150)       │
│ descricao     TEXT               │
│ concluido     CHAR(1)  'S' ou 'N'│
└──────────────────────────────────┘
```

| Coluna         | Tipo           | Significado                                         |
|----------------|----------------|-----------------------------------------------------|
| `id_checklist` | `INT` (auto)   | Identificador **único** da tarefa (a **PK**)        |
| `titulo`       | `VARCHAR(150)` | O nome da tarefa. Obrigatório (`NOT NULL`)          |
| `descricao`    | `TEXT`         | Texto longo, opcional                               |
| `concluido`    | `CHAR(1)`      | `'S'` = concluída, `'N'` = pendente (padrão `'N'`)  |

### 4.2 Conceitos importantes

- **PK (Primary Key / Chave Primária):** identificador **único** de cada linha (o `id_checklist`). Com `AUTO_INCREMENT`, o banco numera sozinho: 1, 2, 3...
- **`NOT NULL`:** o banco **recusa** a linha se esse campo vier vazio.
- **`DEFAULT 'N'`:** se você não informar o valor, o banco usa `'N'`. Toda tarefa nova nasce **pendente**.

### 4.3 Script SQL (`banco.sql`)

```sql
CREATE DATABASE IF NOT EXISTS RChecklist CHARACTER SET utf8;
USE RChecklist;

CREATE TABLE checklist (
    id_checklist INT AUTO_INCREMENT PRIMARY KEY,
    titulo       VARCHAR(150) NOT NULL,
    descricao    TEXT,
    concluido    CHAR(1) NOT NULL DEFAULT 'N'    -- S = feito, N = pendente
);

INSERT INTO checklist (titulo, descricao) VALUES
('Criar o banco de dados', 'Rodar o script banco.sql no phpMyAdmin'),
('Configurar conexao.php', 'Conferir host, usuário e senha'),
('Abrir o sistema no navegador', 'Acessar http://localhost/RChecklist/');
```

> 📝 Se você criou o banco numa versão antiga, que tinha a coluna `ativo`, rode o `atualizar_banco.sql` uma vez. Ele remove essa coluna.

### 4.4 Exclusão real × exclusão lógica

No **RAtendimento2**, "apagar" significa mudar `ativo` de `'S'` para `'N'` (*soft delete*). Aqui o botão **Excluir** faz um `DELETE` de verdade: a linha **some para sempre**.

| | Exclusão lógica (RAtendimento2) | Exclusão real (RChecklist) |
|---|---|---|
| Comando | `UPDATE ... SET ativo = 'N'` | `DELETE FROM ...` |
| O registro continua no banco? | ✅ Sim | ❌ Não |
| Dá para desfazer? | ✅ Sim | ❌ Não |
| Quando usar? | Quando há **histórico** a preservar | Quando o dado é **descartável** (como uma tarefa) |

---

## 5. Estrutura de pastas

```
RChecklist/
│
├── 📄 index.php              ← Redireciona para checklist/listar.php
├── 📄 banco.sql              ← Cria o banco e a tabela
├── 📄 atualizar_banco.sql    ← Só para quem tinha a coluna "ativo"
│
├── 📁 checklist/             ← O CRUD de tarefas
│   ├── listar.php            (tabela + modais + JavaScript)
│   ├── salvar.php            (INSERT e UPDATE)
│   ├── toggleConcluido.php   (marca/desmarca como concluída)
│   └── excluir.php           (DELETE)
│
├── 📁 includes/              ← Peças reaproveitadas
│   ├── conexao.php           (liga com o banco)
│   ├── header.php            (topo do HTML)
│   ├── menu.php              (barra de navegação)
│   └── footer.php            (rodapé do HTML)
│
└── 📁 css/
    └── estilo.css            ← Nossas cores e estilos
```

### 💡 Os arquivos do módulo

| Arquivo               | Função                                                          |
|-----------------------|-----------------------------------------------------------------|
| `listar.php`          | Mostra a **tabela** e contém as **modais** (cadastro, edição, descrição) |
| `salvar.php`          | **Recebe** o formulário e grava no banco (novo ou edição)       |
| `toggleConcluido.php` | **Inverte** o campo `concluido` de uma tarefa                   |
| `excluir.php`         | **Apaga** uma tarefa                                            |

> 🔎 Repare que **não existe `form.php`**. Nos módulos do RAtendimento2, o formulário é uma página separada. Aqui ele vive **dentro de uma modal** no `listar.php`.

---

## 6. A pasta `includes/`: peças reaproveitadas

Imagine montar uma página como montar um lanche: o **pão de cima e o de baixo** são sempre iguais; só o **recheio** muda. Em vez de copiar o pão em todo arquivo, guardamos em `includes/` e **chamamos** quando precisar.

Isso é feito com o comando `require`:

```php
require '../includes/header.php';
```

> Significa: *"pegue o conteúdo desse arquivo e cole aqui"*. O `../` quer dizer "suba uma pasta".

### 6.1 `conexao.php`: a ligação com o banco

```php
$host = "mariadb";
$banco = "RChecklist";
$usuario = "root";
$senha = "mypass";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
```

| Trecho                        | O que faz                                                            |
|-------------------------------|----------------------------------------------------------------------|
| `$host`, `$banco`...          | Guardam os dados de acesso (onde está o banco, nome, usuário, senha) |
| `new PDO(...)`                | Cria a conexão e guarda em `$pdo`                                    |
| `setAttribute(... EXCEPTION)` | Se der erro no SQL, o PHP **avisa** em vez de ficar calado           |
| `try { } catch { }`           | "**Tente** conectar; se der erro, **capture** e mostre a mensagem"   |

> Toda página que usa o banco faz `require '../includes/conexao.php';` e passa a ter a variável `$pdo` disponível.

> 📝 `host = "mariadb"` é o nome do **container Docker** do banco. Sem Docker (XAMPP, WAMP), troque por `localhost`, e no XAMPP a senha costuma ser vazia (`""`).

### 6.2 `header.php`, `menu.php`, `footer.php`: o "pão do lanche"

- **`header.php`**: abre o HTML (`<html>`, `<head>`, `<body>`) e carrega Bootstrap, Bootstrap Icons e o nosso CSS.
- **`menu.php`**: a barra azul de navegação com o nome do sistema.
- **`footer.php`**: rodapé, carrega o JavaScript do Bootstrap e **fecha** `</body></html>`.

Uma página típica fica assim:

```php
<?php
require '../includes/conexao.php';        // 1. Conecta no banco

// ... busca dados no banco ...

require '../includes/header.php';         // 2. Abre o HTML
require '../includes/menu.php';           // 3. Menu azul
?>

<!-- 4. Aqui vai o conteúdo específico da página -->

<?php require '../includes/footer.php'; ?>   <!-- 5. Fecha o HTML -->
```

> 📌 **Regra de ouro:** a lógica PHP (buscar dados, redirecionar) vem **antes** do HTML. Por quê? Porque o `header('Location: ...')` de redirecionamento só funciona **antes** de qualquer HTML ser enviado ao navegador.

---

## 7. O padrão CRUD: o coração do sistema

**CRUD** são as 4 operações básicas de quase todo sistema:

| Letra | Operação | Em português | Comando SQL | Neste projeto                              |
|-------|----------|--------------|-------------|--------------------------------------------|
| **C** | Create   | Criar        | `INSERT`    | `salvar.php` (com `id` vazio)              |
| **R** | Read     | Ler          | `SELECT`    | `listar.php`                               |
| **U** | Update   | Atualizar    | `UPDATE`    | `salvar.php` (com `id`) e `toggleConcluido.php` |
| **D** | Delete   | Apagar       | `DELETE`    | `excluir.php`                              |

### O ciclo de vida de uma tarefa

```
                    ┌───────────────────────────────────────┐
                    │              listar.php               │
                    │   (tabela + modais + checkbox)        │
                    └───┬──────────────┬──────────────┬─────┘
                        │ Salvar       │ Checkbox     │ Excluir
                        │ (POST)       │ (GET)        │ (POST)
                        ▼              ▼              ▼
                  ┌───────────┐ ┌───────────────┐ ┌────────────┐
                  │salvar.php │ │toggleConcluido│ │ excluir.php│
                  │INSERT /   │ │ UPDATE        │ │ DELETE     │
                  │UPDATE     │ │ concluido     │ │            │
                  └─────┬─────┘ └───────┬───────┘ └─────┬──────┘
                        │               │               │
                        └───────────────┴───────────────┘
                                        │
                              redireciona de volta
                                        ▼
                                   listar.php
```

> 🧠 Todos os arquivos "de ação" **não têm tela**. Eles trabalham e **redirecionam** de volta para a lista.

---

## 8. Estudo de caso: o módulo Checklist, arquivo por arquivo

### 8.1 `checklist/listar.php`: mostrando os dados

**Parte 1: buscar no banco (PHP, no topo)**

```php
$sql = "
    SELECT id_checklist, titulo, descricao, concluido
    FROM checklist
    ORDER BY concluido, titulo
";

$itens = $pdo->query($sql)->fetchAll();
```

| Trecho                       | Significado                                          |
|------------------------------|------------------------------------------------------|
| `SELECT ... FROM checklist`  | "Traga essas colunas da tabela checklist"            |
| `ORDER BY concluido, titulo` | Pendentes (`'N'`) primeiro, depois as concluídas (`'S'`); dentro de cada grupo, em ordem alfabética |
| `$pdo->query($sql)`          | Executa a consulta                                   |
| `->fetchAll()`               | Traz **todas** as linhas como uma lista (array)      |

`$itens` agora é uma lista assim:

```php
[
  ['id_checklist' => 1, 'titulo' => 'Criar o banco', 'descricao' => '...', 'concluido' => 'N'],
  ['id_checklist' => 2, 'titulo' => 'Testar o login', 'descricao' => '',   'concluido' => 'S'],
]
```

**Parte 2: o `foreach` (uma linha da tabela por tarefa)**

```php
<?php foreach($itens as $item): ?>
    <tr>
        <td> ...checkbox... </td>
        <td> ...título... </td>
        ...
    </tr>
<?php endforeach; ?>
```

| Sintaxe                     | O que é                                                                   |
|-----------------------------|---------------------------------------------------------------------------|
| `foreach ($lista as $item)` | "**Para cada** item da lista, repita o bloco" (gera um `<tr>` por tarefa) |
| `<?= $variavel ?>`          | Atalho para **imprimir** um valor na página                               |
| `$item['titulo']`           | Pega o campo `titulo` daquela tarefa                                      |

**Parte 3: o checkbox que conclui a tarefa**

```php
<input type="checkbox" class="form-check-input"
       <?= $item['concluido'] == 'S' ? 'checked' : '' ?>
       onchange="window.location='toggleConcluido.php?id=<?= $item['id_checklist'] ?>'">
```

| Trecho                                | Explicação                                                                  |
|---------------------------------------|-----------------------------------------------------------------------------|
| `<?= ... ? 'checked' : '' ?>`         | Operador ternário: se for `'S'`, imprime `checked` e o checkbox vem marcado |
| `onchange="..."`                      | "**Quando mudar**, execute isto". Ao clicar, o navegador vai para outra URL |
| `window.location='toggleConcluido...'`| Leva o navegador até `toggleConcluido.php?id=5`                             |

> `condição ? A : B` é o **operador ternário**: *"se a condição for verdadeira, use A; senão, use B"*.

**Parte 4: o título riscado**

```php
<?php if($item['concluido'] == 'S'): ?>
    <s class="text-muted"><?= htmlspecialchars($item['titulo']) ?></s>
<?php else: ?>
    <?= htmlspecialchars($item['titulo']) ?>
<?php endif; ?>
```

A tag `<s>` do HTML **risca** o texto, e `text-muted` (Bootstrap) deixa-o cinza. Concluída = riscada e cinza.

**Parte 5: o selo de situação**

```php
<?php if($item['concluido'] == 'S'): ?>
    <span class="badge badge-concluido">Concluída</span>
<?php else: ?>
    <span class="badge badge-aberto">Pendente</span>
<?php endif; ?>
```

Mostra um selo **verde** (Concluída) ou **amarelo** (Pendente).

**Parte 6: o botão Excluir (um formulário POST)**

```php
<form action="excluir.php" method="POST" class="d-inline"
      onsubmit="return confirm('Excluir esta tarefa?');">
    <input type="hidden" name="id" value="<?= $item['id_checklist'] ?>">
    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
</form>
```

| Trecho                        | Explicação                                                                       |
|-------------------------------|----------------------------------------------------------------------------------|
| `method="POST"`               | Os dados vão **escondidos**, não na URL                                          |
| `<input type="hidden">`       | Leva o `id` junto, sem aparecer na tela                                          |
| `onsubmit="return confirm()"` | Abre a caixinha "OK / Cancelar". Se clicar em **Cancelar**, o formulário **não é enviado** |
| `class="d-inline"`            | Deixa o formulário na mesma linha dos outros botões                              |

---

### 8.2 `checklist/salvar.php`: gravando no banco (C e U)

É um arquivo que **não tem tela**. Ele trabalha e redireciona.

```php
$id = $_POST['id'];
$titulo = trim($_POST['titulo'] ?? '');

// validação no servidor (o "required" do HTML pode ser burlado)
if ($titulo === '') {
    header('Location: listar.php');
    exit;
}

if (empty($id)) {
    // ID vazio → cadastro novo → INSERT
    $sql = "INSERT INTO checklist (titulo, descricao, concluido)
            VALUES (:titulo, :descricao, 'N')";
} else {
    // ID preenchido → edição → UPDATE
    $sql = "UPDATE checklist
            SET titulo = :titulo, descricao = :descricao
            WHERE id_checklist = :id";
}

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':titulo', $titulo);
$stmt->bindValue(':descricao', $_POST['descricao']);

if (!empty($id)) {
    $stmt->bindValue(':id', $id);
}

$stmt->execute();

header('Location: listar.php');
exit;
```

| Trecho                 | Explicação                                                                              |
|------------------------|-----------------------------------------------------------------------------------------|
| `$_POST['titulo'] ?? ''` | `??` significa *"se não existir, use esse valor padrão"*                              |
| `trim(...)`            | Remove espaços do começo e do fim (evita título só com espaços)                         |
| `if ($titulo === '')`  | **Validação no servidor.** Se vier vazio, volta para a lista sem gravar                 |
| `empty($id)`           | Verdadeiro se o `id` for vazio → **cadastro novo** → `INSERT`                           |
| `else`                 | `id` preenchido → **edição** → `UPDATE`                                                 |
| `'N'` no `VALUES`      | Toda tarefa nova nasce **pendente**, fixado no próprio SQL                              |

> ⚠️ **Cuidado:** o `UPDATE` sempre precisa do `WHERE`. Sem ele, **todas** as tarefas da tabela seriam alteradas!

#### 🔒 O que são os `:titulo`, `:descricao` e o `bindValue`? (Prepared Statements)

Esta é **a técnica de segurança mais importante** do projeto.

**Jeito PERIGOSO** (que **não** usamos):

```php
$sql = "SELECT * FROM checklist WHERE titulo = '" . $_POST['titulo'] . "'";
```

Se alguém digitar no campo: `' OR '1'='1`, o SQL vira uma consulta que devolve **tudo**. Isso é o ataque **SQL Injection**.

**Jeito SEGURO** (que usamos):

```php
$stmt = $pdo->prepare("SELECT * FROM checklist WHERE titulo = :titulo");
$stmt->bindValue(':titulo', $_POST['titulo']);
```

Aqui o SQL e os dados viajam **separados**. O banco trata o que o usuário digitou **sempre como simples texto**, nunca como comando.

> 🧠 Analogia: é como entregar uma **carta lacrada** (o SQL) e, num envelope à parte, os **dados**. Ninguém consegue "colar um comando" dentro da carta.

---

### 8.3 `checklist/toggleConcluido.php`: o interruptor Concluída/Pendente

```php
$id = $_GET['id'];                       // 1. qual tarefa?

// 2. busca a situação atual
$stmt = $pdo->prepare("SELECT concluido FROM checklist WHERE id_checklist = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();
$item = $stmt->fetch();

if ($item) {                             // só continua se a tarefa existir
    // 3. inverte: S vira N, N vira S
    $novoStatus = $item['concluido'] == 'S' ? 'N' : 'S';

    // 4. grava
    $stmt = $pdo->prepare("UPDATE checklist SET concluido = :concluido WHERE id_checklist = :id");
    $stmt->bindValue(':concluido', $novoStatus);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

header('Location: listar.php');          // 5. volta para a lista
exit;
```

Funciona como um **interruptor de luz**: cada clique inverte o estado.

> 🔎 O `if ($item)` protege contra um `id` que não existe. Se `fetch()` não achar nada, ele devolve `false`, e o código simplesmente pula a gravação.

---

### 8.4 `checklist/excluir.php`: apagando (o **D** do CRUD)

```php
// só aceita POST: excluir é uma ação que altera dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM checklist WHERE id_checklist = :id");
    $stmt->bindValue(':id', $_POST['id']);
    $stmt->execute();
}

header('Location: listar.php');
exit;
```

| Trecho                                  | Explicação                                                              |
|-----------------------------------------|-------------------------------------------------------------------------|
| `$_SERVER['REQUEST_METHOD'] === 'POST'` | Confere **como** o pedido chegou. Se alguém abrir `excluir.php` digitando a URL (GET), **nada é apagado** |
| `DELETE FROM ... WHERE ...`             | Apaga **só** a linha com aquele `id`                                    |

> ⚠️ **Cuidado:** assim como o `UPDATE`, o `DELETE` **sempre** precisa do `WHERE`. Sem ele, a tabela inteira seria apagada!

---

## 9. Modais do Bootstrap

Uma **modal** é uma janelinha que aparece **por cima** da página. Neste projeto há dois tipos, e comparar os dois é uma ótima lição.

### 9.1 Modal de descrição: **sem JavaScript nosso**

Existe **uma modal por tarefa**, criada dentro de um `foreach`:

```php
<?php foreach($itens as $item): ?>
    <div class="modal fade" id="modal<?= $item['id_checklist'] ?>" tabindex="-1">
        ...
        <?= nl2br(htmlspecialchars($item['descricao'])) ?>
        ...
    </div>
<?php endforeach; ?>
```

E o título da tarefa, na tabela, é o link que abre a modal:

```html
<a href="#" data-bs-toggle="modal" data-bs-target="#modal5">Título</a>
```

| Atributo                    | Função                                                        |
|-----------------------------|---------------------------------------------------------------|
| `data-bs-toggle="modal"`    | "Este elemento **abre uma modal**"                            |
| `data-bs-target="#modal5"`  | "**Qual** modal?" Aponta para o `id="modal5"`                 |
| `id="modal<?= $item['id_checklist'] ?>"` | O `id` precisa ser **único**, por isso usamos o id da tarefa: `modal5`, `modal6`... |
| `data-bs-dismiss="modal"`   | Usado nos botões **Fechar** e **X**: fecha a modal            |

> 📌 As modais ficam em um **segundo `foreach`**, depois da tabela. Um `<div>` dentro de um `<tr>` não é HTML válido.

### 9.2 Modal de cadastro/edição: **uma só para os dois**

Em vez de duas modais, existe **uma única** (`#modalForm`) que serve para **criar e editar**. Quem decide é o campo oculto `id`:

```html
<input type="hidden" name="id" id="campoId">
```

| `id`          | O que o `salvar.php` faz | Título da modal   |
|---------------|--------------------------|-------------------|
| vazio         | `INSERT` (cria)          | **Nova Tarefa**   |
| preenchido    | `UPDATE` (edita)         | **Editar Tarefa** |

É a mesma ideia do `form.php` do RAtendimento2 (um formulário para criar e editar), só que dentro de uma modal.

O botão **Nova Tarefa** leva o `id` vazio, e o botão **Editar** leva os dados da tarefa em atributos `data-*`:

```php
<!-- Nova Tarefa -->
<button data-bs-toggle="modal" data-bs-target="#modalForm" data-id="">

<!-- Editar -->
<button data-bs-toggle="modal" data-bs-target="#modalForm"
        data-id="<?= $item['id_checklist'] ?>"
        data-titulo="<?= htmlspecialchars($item['titulo'], ENT_QUOTES) ?>"
        data-descricao="<?= htmlspecialchars($item['descricao'], ENT_QUOTES) ?>">
```

> 🔒 O `ENT_QUOTES` faz as **aspas** virarem códigos seguros. Sem ele, uma tarefa com `"` no título **quebraria o HTML** do botão (e seria uma brecha de XSS). Teste cadastrando uma tarefa com aspas!

---

## 10. O pequeno JavaScript do projeto

Diferente do RAtendimento2 (que só usa o JavaScript do Bootstrap), aqui há **um trecho nosso**, no final do `listar.php`. Ele serve para **preencher a modal** quando ela abre.

```html
<script>
document.getElementById('modalForm').addEventListener('show.bs.modal', function (event) {
    var botao = event.relatedTarget;
    var id = botao.dataset.id || '';

    document.getElementById('campoId').value = id;
    document.getElementById('campoTitulo').value = botao.dataset.titulo || '';
    document.getElementById('campoDescricao').value = botao.dataset.descricao || '';
    document.getElementById('modalFormTitulo').textContent = id ? 'Editar Tarefa' : 'Nova Tarefa';
});
</script>
```

Passo a passo:

| Linha | O que acontece |
|-------|----------------|
| `getElementById('modalForm')` | Encontra a modal na página |
| `addEventListener('show.bs.modal', ...)` | "**Quando** a modal for abrir, execute esta função". O Bootstrap dispara esse evento |
| `event.relatedTarget` | O **botão que foi clicado** (Nova Tarefa ou Editar) |
| `botao.dataset.titulo` | Lê o atributo `data-titulo` do botão |
| `... \|\| ''` | "Se não existir, use texto vazio". É o que limpa os campos no cadastro novo |
| `.value = ...` | Escreve o valor dentro do campo do formulário |
| `id ? 'Editar' : 'Nova'` | O mesmo operador ternário do PHP, agora em JavaScript |

> 💡 **PHP × JavaScript:** o PHP roda **no servidor, antes** da página chegar. O JavaScript roda **no navegador, depois**. Por isso o PHP coloca os dados nos `data-*` e o JavaScript os lê.

> 🧠 O `salvar.php` **não sabe** se o pedido veio de uma página ou de uma modal. Ele só recebe um POST. A interface pode mudar à vontade, e o back-end continua igual.

---

## 11. O visual: Bootstrap e CSS

### 11.1 Bootstrap: CSS pronto via CDN

No `header.php`:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
```

**CDN** = o arquivo é carregado da internet (não precisa baixar nada). Por isso o projeto **precisa de internet** para ficar bonito e para as modais funcionarem.

### 11.2 Classes do Bootstrap que aparecem no projeto

| Classe                                      | O que faz                                                |
|---------------------------------------------|----------------------------------------------------------|
| `container`                                 | Centraliza e limita a largura do conteúdo                |
| `card`, `shadow-sm`                         | Caixinha com borda e sombra                              |
| `table table-striped table-hover`           | Tabela zebrada que destaca a linha ao passar o mouse     |
| `align-middle`                              | Alinha o conteúdo das células ao meio                    |
| `btn btn-sm btn-danger`                     | Botão pequeno e vermelho                                 |
| `form-control`, `form-label`                | Campos e rótulos de formulário estilizados               |
| `form-check-input`                          | O visual do checkbox                                     |
| `modal`, `modal-dialog`, `modal-content`... | As peças de uma modal                                    |
| `badge`                                     | Selinho colorido (Pendente/Concluída)                    |
| `text-muted`                                | Texto cinza                                              |
| `d-flex`, `ms-auto`, `d-inline`             | Organizam itens lado a lado; `ms-auto` empurra para a direita |
| `mb-3`, `mt-4`                              | **Margens** (m = margin; b = bottom; t = top)            |

### 11.3 O nosso `css/estilo.css`

Aqui definimos a **identidade visual** (azul e amarelo), a mesma do RAtendimento2.

**Variáveis CSS**, definidas uma vez e usadas em vários lugares:

```css
:root {
    --eeepe-azul: #0D47A1;
    --eeepe-amarelo: #FFC107;
}

.navbar-eeepe {
    background-color: var(--eeepe-azul);
}
```

> 💡 Quer mudar a cor do sistema inteiro? Troque **uma linha** no `:root`.

**Classes personalizadas** do projeto:

| Classe               | Uso                                    |
|----------------------|----------------------------------------|
| `.navbar-eeepe`      | Barra de navegação azul                |
| `.btn-eeepe`         | Botão principal azul (Nova Tarefa, Salvar) |
| `.btn-eeepe-warning` | Botão "Editar" amarelo                 |
| `.badge-aberto`      | Selo amarelo de **Pendente**           |
| `.badge-concluido`   | Selo verde de **Concluída**            |

### 11.4 Ícones

```html
<i class="bi bi-trash"></i>
```

Vêm do **Bootstrap Icons**. Para achar outros: https://icons.getbootstrap.com

---

## 12. Como rodar o projeto

### 12.1 O que você precisa

1. Um servidor web com **PHP** (Apache/Nginx)
2. Um banco **MariaDB/MySQL**
3. O banco `RChecklist` criado (rode o `banco.sql`)
4. **Internet** (para carregar o Bootstrap)

### 12.2 Opção A: com Docker

O `conexao.php` aponta para `host = "mariadb"`, que é o **nome de um serviço Docker**. Um `docker-compose.yml` típico:

```yaml
services:
  web:
    image: php:8-apache
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    depends_on:
      - mariadb
    # Atenção: a imagem php:apache precisa da extensão pdo_mysql
    # (instale com: docker-php-ext-install pdo_mysql)

  mariadb:
    image: mariadb:11
    environment:
      MARIADB_ROOT_PASSWORD: mypass
      MARIADB_DATABASE: RChecklist
```

Depois, acesse: `http://localhost:8080/RChecklist/`

### 12.3 Opção B: XAMPP / WAMP / Laragon

1. Copie a pasta `RChecklist` para `htdocs` (XAMPP).
2. Abra o phpMyAdmin e rode o `banco.sql`.
3. Em `includes/conexao.php`, troque:

   ```php
   $host = "localhost";
   $usuario = "root";
   $senha = "";          // no XAMPP a senha costuma ser vazia
   ```
4. Acesse `http://localhost/RChecklist/`

### 12.4 Problemas comuns

| Sintoma | Causa provável | Solução |
|---------|----------------|---------|
| `Permission denied` / `Failed opening required` | O Apache não tem permissão de leitura nos arquivos | `chmod -R a+rX RChecklist` (ou `chown -R www-data:www-data` dentro do container) |
| `404 Not Found` | Subpastas fora do lugar, ou URL errada para o volume montado | Confira a estrutura da [seção 5](#5-estrutura-de-pastas) e o caminho do volume |
| `Erro na conexão` | `host`, usuário ou senha errados | Ajuste o `conexao.php` ([seção 6.1](#61-conexaophp-a-ligação-com-o-banco)) |
| Página sem estilo / modais não abrem | Sem internet (CDN do Bootstrap) | Conecte-se à internet |
| `could not find driver` | Falta a extensão `pdo_mysql` no PHP | `docker-php-ext-install pdo_mysql` |

---

## 13. ⚠️ Pontos de atenção (o que melhorar)

> Este é um projeto **de aprendizado**, e está muito bom para isso! Mas um bom desenvolvedor sabe reconhecer o que **não** faria em produção. Veja os pontos abaixo como **oportunidades de melhoria**.

### 🔴 Segurança

#### 1. Não há login

Qualquer pessoa que acessar o endereço pode ver, editar e excluir **todas** as tarefas. Em um sistema real, haveria autenticação (veja o login por sessão do RAtendimento2).

#### 2. `toggleConcluido.php` altera dados via GET

O checkbox chama `toggleConcluido.php?id=3`, e basta **acessar o link** para mudar o dado. Navegadores e extensões às vezes "pré-carregam" links, e alguém pode te enganar com um link malicioso. Ações que **alteram dados** deveriam usar **POST**. Repare que o `excluir.php` **já faz isso do jeito certo**.

#### 3. Credenciais do banco dentro do código

`root` / `mypass` estão escritas no `conexao.php`. Em produção, usa-se **variáveis de ambiente** e um usuário do banco com **permissões limitadas**, nunca o `root`.

#### 4. Sem proteção contra CSRF

Um site malicioso poderia fazer o navegador de alguém enviar um POST para o `excluir.php`. A defesa é um **token CSRF** em cada formulário. É um tema avançado, mas vale conhecer o nome.

### 🟡 Comportamento

#### 5. A página recarrega a cada checkbox

Marcar uma tarefa recarrega a página inteira e o scroll volta ao topo. Dá para resolver com `fetch()` em JavaScript, atualizando só a linha.

#### 6. Mensagens ao usuário

Se o título vier vazio, o `salvar.php` simplesmente volta para a lista, **sem avisar** o motivo.

#### 7. Excluir é definitivo

Não há lixeira nem "desfazer". Veja a [seção 4.4](#44-exclusão-real--exclusão-lógica) para o debate entre exclusão real e lógica.

#### 8. Classes de CSS sobrando

O `estilo.css` ainda traz `.badge-ativo` e `.badge-inativo`, que não são mais usados, pois a coluna `ativo` foi removida.

### 🟢 Boas práticas que faltam

- **Validar mais no servidor:** limitar o tamanho do título (150 caracteres, como no banco).
- **Tratar o `descricao` ausente:** o `$_POST['descricao']` não usa `??`.
- **Paginação e busca:** com milhares de tarefas, a lista ficará enorme.
- **Tratar erros do banco:** hoje uma falha no `execute()` gera um erro cru na tela.

---

## 14. Exercícios para praticar

### 🟢 Nível 1: Iniciante

1. Mude a cor azul do sistema para verde alterando **apenas** o `:root` do CSS.
2. Troque o texto do rodapé em `footer.php`.
3. Altere o título da aba do navegador em `header.php`.
4. Troque o texto "Pendente" por "A fazer" no `listar.php`.

### 🟡 Nível 2: Intermediário

5. Cadastre uma tarefa com aspas (`"`) no título e confira se a edição funciona. Depois **remova** o `ENT_QUOTES` e veja o que quebra.
6. Adicione o campo **prazo** (`DATE`) à tarefa: banco → modal → `salvar.php` → `listar.php`.
7. Adicione um **contador** acima da tabela: "X pendentes, Y concluídas" (dica: `SELECT COUNT(*) ... WHERE concluido = 'N'`).
8. Mostre uma **mensagem de erro** quando o título vier vazio (dica: redirecione com `?erro=1` e use `isset($_GET['erro'])`).
9. Limite o título a 150 caracteres: use `maxlength="150"` no HTML **e** valide no PHP com `strlen()`.

### 🔴 Nível 3: Desafio

10. Transforme o `toggleConcluido.php` para funcionar via **POST**.
11. Troque o `confirm()` do Excluir por uma **modal do Bootstrap** de confirmação.
12. Faça o checkbox funcionar **sem recarregar a página**, usando `fetch()`.
13. Adicione um **campo de busca** por título (dica: `WHERE titulo LIKE :busca`).
14. Crie a tabela `usuario` e um **login por sessão**, como no RAtendimento2, para que cada pessoa veja só as suas tarefas.

---

## 15. Glossário

| Termo                  | Significado simples                                                          |
|------------------------|------------------------------------------------------------------------------|
| **Aplicação WEB**      | Programa que roda em um servidor e é acessado pelo navegador                 |
| **Servidor**           | Computador que recebe pedidos e devolve páginas                              |
| **Request / Response** | Pedido do navegador / resposta do servidor                                   |
| **HTML**               | Linguagem que define a **estrutura** da página                               |
| **CSS**                | Linguagem que define a **aparência**                                         |
| **JavaScript**         | Linguagem que roda no **navegador** e dá comportamento à página              |
| **PHP**                | Linguagem que roda no **servidor** e gera o HTML                             |
| **Framework**          | Conjunto de ferramentas prontas que organiza o projeto                       |
| **Bootstrap**          | Biblioteca de CSS (e JS) pronta                                              |
| **CDN**                | Servidor na internet que hospeda arquivos como o Bootstrap                   |
| **Modal**              | Janelinha que aparece por cima da página                                     |
| **Evento**             | Algo que acontece na página (um clique, uma modal abrindo) e que o JS pode "escutar" |
| **`data-*`**           | Atributos HTML personalizados, usados para guardar dados num elemento        |
| **SQL**                | Linguagem para conversar com o banco de dados                                |
| **PDO**                | Ferramenta do PHP para conectar e consultar bancos                           |
| **Prepared Statement** | Consulta SQL com "buracos" (`:titulo`) preenchidos com segurança             |
| **SQL Injection**      | Ataque que injeta comandos SQL por meio de campos de formulário              |
| **XSS**                | Ataque que injeta JavaScript malicioso em uma página                         |
| **CSRF**               | Ataque que faz o navegador da vítima enviar um pedido sem ela perceber       |
| **CRUD**               | Create, Read, Update, Delete (criar, ler, atualizar, apagar)                 |
| **PK**                 | Chave primária: identificador único de cada linha                            |
| **NULL**               | Ausência de valor (diferente de texto vazio ou zero)                         |
| **Redirecionamento**   | Mandar o navegador para outra página (`header('Location: ...')`)             |
| **GET / POST**         | Formas de enviar dados: pela URL / pelo corpo do pedido                      |
| **Operador ternário**  | `condição ? A : B`, um "se/senão" em uma linha                               |
| **Soft delete**        | Marcar como inativo em vez de apagar de verdade                              |
| **Container (Docker)** | "Caixa" isolada que roda um serviço (PHP, banco...)                          |

---

## 🧭 Roteiro de estudo sugerido

1. Leia as seções **2 e 3** (conceitos).
2. Monte o ambiente e **rode o projeto** (seção 12).
3. Abra `includes/` e acompanhe a [seção 6](#6-a-pasta-includes-peças-reaproveitadas).
4. Estude o `listar.php` ([seção 8.1](#81-checklistlistarphp-mostrando-os-dados)).
5. Siga o caminho dos dados: `salvar.php` → `toggleConcluido.php` → `excluir.php`.
6. Entenda as modais e o JavaScript ([seção 9](#9-modais-do-bootstrap) e [10](#10-o-pequeno-javascript-do-projeto)).
7. Faça os **exercícios** e depois releia a [seção 13](#13-️-pontos-de-atenção-o-que-melhorar).

> 💬 **Dica final:** não decore código. Entenda o **fluxo** (listar → ação → redirecionar → listar) e o resto vira variação do mesmo tema. Bons estudos! 🚀
