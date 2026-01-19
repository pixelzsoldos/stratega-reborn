import { JwtService } from '@nestjs/jwt';
export declare class AuthService {
    private readonly jwtService;
    constructor(jwtService: JwtService);
    login(username: string, password: string): Promise<any>;
    validateUser(username: string, password: string): Promise<any>;
}
