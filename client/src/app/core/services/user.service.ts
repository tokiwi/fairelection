import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { User } from '../models/user.model';
import { map } from 'rxjs/operators';


@Injectable()
export class UserService {
    constructor(
        private api: ApiService,
    ) {
    }

    public register(payload: Record<string, any>): Observable<User> {
        return this.api.post('/register', payload).pipe(
            map((res) => res as User)
        );
    }

    public resetPasswordRequest(body: Record<any, unknown>): Observable<any> {
        return this.api.post('/forgot_password/', body);
    }

    public resetPassword(body: Record<any, unknown>, token: string): Observable<any> {

        return this.api.post('/forgot_password/' + token, body);
    }
}
