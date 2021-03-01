import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';


@Injectable()
export class JwtService {

    public static getToken(): string|null {
        return window.localStorage.getItem(environment.jwtTokenKey);
    }

    public static saveToken(token: string): void {
        window.localStorage.setItem(environment.jwtTokenKey, token);
    }

    public static destroyToken(): void {
        window.localStorage.removeItem(environment.jwtTokenKey);
    }
}
