````markdown
# ContactList

## Descrição

A **ContactList** é um sistema web moderno para gerenciar informações de contatos de forma prática e eficiente. Desenvolvido com as últimas tecnologias do mercado, o projeto oferece:

- **Design 100% responsivo**: adapta-se perfeitamente a qualquer tamanho de tela.  
- **Dark Mode**: tema escuro para conforto visual em ambientes com pouca luz.  
- **Design moderno**: interface limpa, com listas dinâmicas e filtros em tempo real no client‑side.  
- **CRUD completo**: criação, leitura, atualização e exclusão de contatos, com validação de inputs e tratamento de erros robusto.

## Tecnologias Utilizadas

- **Backend**: Laravel 12  
- **Frontend**: Vue 3, Shadcn, TailwindCSS  
- **Banco de Dados**: MySQL 5.7  

## Funcionalidades Principais

1. **Gerenciamento de Contatos**  
   - Cadastro de novos contatos  
   - Edição e exclusão de contatos existentes  
   - Visualização de detalhes de cada contato  

2. **Filtros e Listas Dinâmicas**  
   - Busca e filtro de contatos em tempo real no lado do cliente  
   - Ordenação por nome, data de criação etc.  

3. **Validação e Tratamento de Erros**  
   - Validação de campos no frontend e backend  
   - Exibição de mensagens de erro claras para o usuário  

4. **Acessibilidade e Usabilidade**  
   - Suporte a navegação por teclado  
   - Contraste adequado em ambos temas (claro e escuro)  

## Instalação

1. Clone o repositório:  
   ```bash
   git clone https://github.com/joaovtfor/contactlist.git
````

2. Acesse a pasta do projeto:

   ```bash
   cd contactlist
   ```
3. Instale as dependências do backend:

   ```bash
   composer install
   ```
4. Instale as dependências do frontend:

   ```bash
   npm install
   ```
5. Configure o arquivo `.env` com as credenciais do MySQL.
6. Execute migrations e seeders:

   ```bash
   php artisan migrate --seed
   ```
7. Inicie o servidor de desenvolvimento:

   ```bash
   php artisan serve
   npm run dev
   ```
   ou então

   ```bash
   composer run dev
   ```

## Contribuição

1. Faça um fork do projeto.
2. Crie uma branch com sua feature:

   ```bash
   git checkout -b feature/nome-da-feature
   ```
3. Faça commit das suas alterações:

   ```bash
   git commit -m "Adiciona [sua feature]"
   ```
4. Envie para o repositório remoto:

   ```bash
   git push origin feature/nome-da-feature
   ```
5. Abra um Pull Request.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

```
```
