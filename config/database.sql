create database musifdb;
use musifdb;
create table usuario(
id int primary key auto_increment,
matricula long unique not null,
email varchar(200) unique not null,
senha varchar(200) not null,
nome varchar(200) not null);
insert into usuario(matricula, email, senha, nome) values (20241134010011, "j.gabriel5178@gmail.com", "$2y$10$Bpncj4gho00XJNaxzGSh1OBiTKz3AZEpEYFSQ144.Z2PRwRzp/N1W", "José Gabriel");
select * from usuario;