import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { delay } from 'rxjs/operators';


@Injectable()
export class LoadingService {
    public loadingSubject = new BehaviorSubject<boolean>(false);
    public loading$ = this.loadingSubject.asObservable().pipe(
        delay(0)
    );

    private loadingMap: Map<string, boolean> = new Map<string, boolean>();

    public setLoading(url: string, loading: boolean): void {
        if (!url) {
            throw new Error('URL must be provided to setLoading function');
        }

        if (true === loading) {
            this.loadingMap.set(url, loading);
            this.loadingSubject.next(true);
        } else if (false === loading && this.loadingMap.has(url)) {
            this.loadingMap.delete(url);
        }
        if (0 === this.loadingMap.size) {
            this.loadingSubject.next(false);
        }
    }

    public startLoading(): void {
        this.loadingSubject.next(true);
    }

    public stopLoading(): void {
        this.loadingSubject.next(false);
    }

    public isLoading(): boolean {
        return this.loadingSubject.getValue();
    }
}
