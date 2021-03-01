import { Injectable } from '@angular/core';
import { Observable, ReplaySubject } from 'rxjs';
import { ApiService } from './api.service';
import { map } from 'rxjs/operators';
import { SolverResource } from '../models/solver-resource.model';
import { HttpParamUtil } from '../utils/http-param.util';

@Injectable()
export class SolverResourceService {
    public isSolvableSubject: ReplaySubject<boolean> = new ReplaySubject<boolean>(1);
    public isSolvable: Observable<boolean> = this.isSolvableSubject.asObservable(); // eslint-disable-line

    constructor(
        private api: ApiService
    ) {
        this.isSolvableSubject.next(false);
    }

    public solveByElection(ulid: string, persistResults = true): Observable<SolverResource> {
        const params = HttpParamUtil.create();
        params.add('election', ulid);
        params.add('error', 'false');

        if (!persistResults) {
            params.add('persist', 'false');
        }

        // hide-toast-error
        return this.api.post('/solver_resources', {}, {
            params: params.getHttpParams(),
        }).pipe(
            map((res) => res as SolverResource)
        );
    }
}
