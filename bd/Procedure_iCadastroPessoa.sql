/* exemplo de Stored Procedure */
delimiter ##
drop procedure if exists iCadastroPessoa ##
create procedure iCadastroPessoa
(in nomeP varchar(255), dtNascP date, loginP varchar(65), 
senhaP varchar(64), perfilP varchar(20), emailP varchar(255), 
cpfP varchar(14), cepP varchar(10), logradouroP varchar(255), 
complementoP varchar(255), bairroP varchar(255), cidadeP varchar(255), 
ufP varchar(2), out msg text)
begin
declare idx int(11) default -1;
declare idp int(11) default 0;

select idpessoa into idp from pessoa where cpf = cpfP;
if(idp > 0)then
    set msg = "Usuário cadastrado anteriormente.";
else
    select idendereco into idx from endereco where 
	logradouro = logradouroP and complemento = complementoP and 
	cep = cepP;
    if(idx = -1) then
        insert into endereco values (null, logradouroP, complementoP,
           bairroP, cidadeP, ufP, cepP);
        select idendereco into idx from endereco where 
            logradouro = logradouroP and complemento = complementoP and 
            cep = cepP;
    end if;
    insert into pessoa values (null, nomeP, dtNascP, loginP,
        md5(senhaP), perfilP, emailP, cpfP, idx);
    set msg = "Dados cadastrados com sucesso.";
end if;
select msg;
end ##
delimiter ;

call iCadastroPessoa("Roberto Carlos", "1968-03-23", "mariag", 
"maria123", "Cliente", "maria@bol.com.br", "262.267.790-12", 
"01001-000", "Praça da Sé", "lado par", "Sé", "São Paulo", 
"SP", @msg);
