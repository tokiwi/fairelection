import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { map } from 'rxjs/operators';
import { HttpParams } from '@angular/common/http';
import { Candidate } from '../models/candidate.model';
import { HydraCollection } from '../models/hydra-collection.model';

@Injectable()
export class CandidateService {
    constructor(
        private api: ApiService
    ) {}

    public findByElection(ulid: string): Observable<Candidate[]> {
        const params = new HttpParams().set('election', ulid);

        return this.api.get('/candidates', {params}).pipe(
            map((res: HydraCollection) => res['hydra:member'] as Candidate[])
        );
    }

    public bulkUpdate(body: Record<any, unknown>): Observable<void> {
        return this.api.post('/candidates/votes', body).pipe(
            map((res) => res as void)
        );
    }
}
