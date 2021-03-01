import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { ApiService } from './api.service';
import { Election } from '../models/election.model';
import { ElectionCriteria } from '../models/election-criteria.model';

@Injectable()
export class ElectionCriteriaService {
    constructor(
        private api: ApiService
    ) {}

    public add(body: Record<any, unknown>): Observable<ElectionCriteria> {
        return this.api.post('/election_criterias', body).pipe(
            map((res) => res as ElectionCriteria)
        );
    }

    public remove(iri: string): Observable<any> {
        return this.api.delete(iri).pipe(
            map((res) => res as Election)
        );
    }
}
