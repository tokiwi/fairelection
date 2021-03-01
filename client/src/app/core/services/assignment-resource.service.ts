import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

@Injectable()
export class AssignmentResourceService {
    constructor(
        private api: ApiService
    ) {}

    public save(body: Record<any, unknown>): Observable<any> {
        return this.api.post('/assignment_resources', body);
    }
}
