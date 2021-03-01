import { EventEmitter, Injectable } from '@angular/core';
import { BehaviorSubject, Observable, ReplaySubject } from 'rxjs';

import { ApiService } from './api.service';
import { map } from 'rxjs/operators';
import { JwtService } from './jwt.service';
import { JwtHelperService } from '@auth0/angular-jwt';
import { User } from '../models/user.model';
import { Router } from '@angular/router';


@Injectable()
export class AuthService {
    public authEvent = new EventEmitter<void>();
    public purgeAuthEvent = new EventEmitter<void>();

    private isAuthenticatedSubject = new ReplaySubject<boolean>(1);
    public isAuthenticated = this.isAuthenticatedSubject.asObservable(); // eslint-disable-line

    private userSubject = new BehaviorSubject<User>
    ({email: '', fullName: '', roles: []}); // init first blank user

    public user = this.userSubject.asObservable(); // eslint-disable-line

    constructor(
        private apiService: ApiService,
        private route: Router
    ) {
    }

    /**
     * Login the user based on token stored in local storage
     */
    public autoLogin(): void {
        // If JWT detected, attempt to get & store user's info
        const token = JwtService.getToken();

        if (token) {
            this.setAuth({token});
        } else {
            // Remove any potential remnants of previous auth states
            this.purgeAuth();
        }
    }

    public login(credentials: {password: string; email: string}): Observable<any> {
        return this.apiService.post('/authenticate', credentials).pipe(map(
            data => {
                this.setAuth(data);

                return data; // eslint-disable-line
            }
        ));
    }

    /**
     * Logout the user by removing local storage token and setting
     * authenticatedSubject to false.
     */
    public logout(): void {
        this.purgeAuth();
    }

    public getUser(): User {
        return this.userSubject.getValue();
    }

    private purgeAuth(): void {
        // Remove JWT from localstorage
        JwtService.destroyToken();

        // Set auth status to false
        this.isAuthenticatedSubject.next(false);
        this.purgeAuthEvent.emit();
    }

    private setAuth(data: {token: string}): void {
        const helper = new JwtHelperService();
        const isExpired = helper.isTokenExpired(data.token);

        if (!isExpired) {
            JwtService.saveToken(data.token);
            this.isAuthenticatedSubject.next(true);

            // const decodedToken = helper.decodeToken(data.token);

            // this.userSubject.next({
            //     roles: decodedToken.roles,
            // });
        } else {
            this.purgeAuth();
        }
    }
}
