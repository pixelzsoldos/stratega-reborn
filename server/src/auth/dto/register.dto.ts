// src/auth/dto/register.dto.ts
import { IsString, IsEmail } from 'class-validator';

export class RegisterDto {
  @IsString()
  username: string;

  @IsEmail()
  email: string;

  @IsString()
  password: string;

  @IsString()
  race: string;
}