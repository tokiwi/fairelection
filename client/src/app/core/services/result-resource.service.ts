import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { map } from 'rxjs/operators';
import { ResultResource } from '../models/result-resource.model';

@Injectable()
export class ResultResourceService {
    constructor(
        private api: ApiService
    ) {}

    public getByElection(ulid: string): Observable<ResultResource> {
        return this.api.get('/result_resources/' + ulid).pipe(
            map((res) => res as ResultResource)
        );
    }
}
