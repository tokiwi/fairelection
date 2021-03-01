import { Injectable } from '@angular/core';

@Injectable()
export class LocalStorageService {
    public static get(key: string): string|null {
        return window.localStorage.getItem(key);
    }

    public static set(key: string, value: string): void {
        window.localStorage.setItem(key, value);
    }
}
