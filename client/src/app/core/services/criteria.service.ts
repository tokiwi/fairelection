import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { ApiService } from './api.service';
import { Criteria } from '../models/criteria.model';
import { HydraCollection } from '../models/hydra-collection.model';
import { HttpParamUtil } from '../utils/http-param.util';

@Injectable()
export class CriteriaService {
    constructor(
        private api: ApiService
    ) {}

    public findAll(iri: string): Observable<Criteria[]> {
        const param = HttpParamUtil.create();
        param.add('election', iri);

        return this.api.get('/criterias', {params: param.getHttpParams()}).pipe(
            map((res: HydraCollection) => res['hydra:member'] as Criteria[])
        );
    }

    public create(body: Record<any, unknown>): Observable<Criteria> {
        return this.api.post('/criterias', body).pipe(
            map((res) => res as Criteria)
        );
    }

    public update(iri: string, body: Record<any, unknown>): Observable<Criteria> {
        return this.api.put(iri, body).pipe(
            map((res) => res as Criteria)
        );
    }
}
