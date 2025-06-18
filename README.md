# Projeto - Melhorias e Ajustes

Teste técnico - backend Cartões - VEXPENSES

---

## Melhorias e Ajustes Realizados

- **Remoção do middleware `auth.basic` da rota de login**  
  O middleware exigia autenticação básica para acessar a própria rota de login, o que não fazia sentido. A autenticação agora ocorre dentro do método, tornando o fluxo mais natural.

- **Validação aprimorada de login**  
  A validação agora usa os campos `email` e `password`, que são informados e salvos no cadastro. Isso deixa o fluxo de autenticação mais claro e alinhado com práticas comuns de APIs.

- **Correção de problema no cadastro de usuários com CPF, CNPJ e e-mail duplicados**  
  Quando o usuário tentava cadastrar um CPF ou e-mail já existente, mas com um CNPJ diferente, o sistema criava uma nova empresa, porém falhava na criação do usuário, deixando a empresa salva sem vínculo.  
  Para resolver, o processo de criação de usuário e empresa agora está dentro de uma transação de banco de dados (`DB::transaction`). Assim, se qualquer parte falhar, nada é salvo.

- **Mensagens de erro aprimoradas**  
  O sistema agora informa exatamente qual dado está duplicado: CPF, CNPJ ou e-mail. Isso facilita o entendimento e uso da API.

- **Refatoração da atualização da empresa seguindo DDD**  
  A lógica de update foi removida da controller e encapsulada em um Use Case específico (`UseCases/Company/Update`), com um objeto de parâmetros (`UseCases/Params/Company/UpdateParams`).

- **Refatoração do CardController para uso de Use Cases**  
  O endpoint de exibição de cartão (`CardController@show`) foi refatorado para usar um Use Case (`UseCases/Card/Show`) que encapsula a lógica de busca e tratamento de erros, deixando a controller mais limpa e o código mais testável.

- **Separação das responsabilidades de autenticação**  
  As funcionalidades de login e registro foram desacopladas da controller de usuários, criando uma `AuthController` dedicada. A lógica de autenticação foi encapsulada em um Use Case (`AuthenticateUser`), seguindo a arquitetura limpa.

- **Uso de Resources para padronização das respostas**  
  Para o `CardController`, foram criados Resources específicos (`ShowResource` e `CreateResource`) para encapsular os dados retornados nas ações de show e create, garantindo respostas consistentes e organizadas.

- **Refatoração do teste de login (`tests/Feature/User/LoginTest.php`)**  
  Removi o uso do header de autenticação básica e passei as credenciais diretamente no corpo da requisição, deixando o teste mais alinhado com a lógica atual da API.
  

## Melhorias não implementadas

### Nomes de arquivos e classes em lowercase

- Alguns arquivos e classes estão com nomes em lowercase, por exemplo:  
  `App\UseCases\User\show`  
  
  O correto seria usar PascalCase, assim:  
  `App\UseCases\User\Show`  
  
  Essa diferença pode causar problemas com autoload, vai contra o SOLID e deixa o projeto menos organizado.

### Falta de interfaces para Use Cases

- Os Use Cases estão lá, mas não tem nenhuma interface definida pra eles ou pra outras partes do código.  

  Isso pode complicar pra trocar coisas, fazer testes ou dar manutenção depois, porque quebra um pouco a ideia do SOLID de depender de abstrações.  

  Ter interfaces ajuda a deixar o código mais flexível, desacoplado e fácil de mexer.

  ## Observação

O teste em si não pedia modificações diretas no código, já que a avaliação será feita definitivamente na conversa com os gestores.  
Porém, para um melhor entendimento do código, fiz o clone do projeto, rodei as migrations e testei endpoint por endpoint para entender o fluxo do sistema.  

Nesse fluxo, nos endpoints de `card` e `company`, tive um problema com a integração do BaaS (Banking as a Service), pois a URL configurada no arquivo `.env` (`https://api.banking.com.br/`) está fora do ar.  

Com isso, mockei o retorno dessas integrações para que meus testes continuassem e o fluxo do sistema seguisse normalmente.  

Dessa forma, consegui entender melhor a regra de negócio e onde cada coisa estava acontecendo, validando assim 100% dos endpoints.
