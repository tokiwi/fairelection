import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { map } from 'rxjs/operators';
import { CandidateResource } from '../models/candidate-resource.model';

@Injectable()
export class CandidateResourceService {
    constructor(
        private api: ApiService
    ) {}

    public save(body: Record<any, unknown>): Observable<any> {
        return this.api.post('/candidate_resources', body);
    }

    public findByElection(ulid: string): Observable<CandidateResource> {
        return this.api.get('/candidate_resources/' + ulid).pipe(
            map((res) => res as CandidateResource)
        );
    }
}
