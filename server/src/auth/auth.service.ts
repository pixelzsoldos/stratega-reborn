import { Injectable, UnauthorizedException } from "@nestjs/common";
import { JwtService } from "./jwt.service";
import { LoginDto } from "./dto/login.dto";
import { RegisterDto } from "./dto/register.dto";

/**
 * AuthService handles authentication logic, using DTOs for clarity.
 * In a production setting this would interface with a user repository
 * and handle password hashing. For this example we provide minimal,
 * illustrative implementations.
 */
@Injectable()
export class AuthService {
  constructor(private readonly jwtService: JwtService) {}

  /**
   * Authenticate a user and return a JWT token.
   * @param loginDto - The login data transfer object.
   * @returns An object containing the access token.
   * @throws {UnauthorizedException} if credentials are invalid.
   */
  async login(loginDto: LoginDto): Promise<{ access_token: string }> {
    const user = await this.validateUser(loginDto.email, loginDto.password);
    if (!user) {
      throw new UnauthorizedException("Invalid credentials");
    }
    const payload = { sub: user.id, email: user.email };
    return { access_token: this.jwtService.sign(payload) };
  }

  /**
   * Register a new user and return a JWT token.
   * @param registerDto - The registration data transfer object.
   * @returns An object containing the access token.
   */
  async register(registerDto: RegisterDto): Promise<{ access_token: string }> {
    // Stub: pretend the user was created.
    const user = {
      id: Math.random().toString(36).substring(2, 15),
      email: registerDto.email,
    };
    const payload = { sub: user.id, email: user.email };
    return { access_token: this.jwtService.sign(payload) };
  }

  /**
   * Validate user credentials.
   * @param email - User email.
   * @param password - User password.
   * @returns A user object or null if validation fails.
   */
  private async validateUser(
    email: string,
    password: string,
  ): Promise<{ id: string; email: string } | null> {
    // Example hard‑coded user for demonstration purposes.
    const dummyUser = {
      id: "user123",
      email: "test@example.com",
      password: "secret",
    };

    if (email === dummyUser.email && password === dummyUser.password) {
      return { id: dummyUser.id, email: dummyUser.email };
    }
    return null;
  }
}
