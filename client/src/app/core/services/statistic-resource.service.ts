import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { map } from 'rxjs/operators';
import { StatisticResource } from '../models/statistic-resource.model';
import { HttpParamUtil } from '../utils/http-param.util';

@Injectable()
export class StatisticResourceService {
    constructor(
        private api: ApiService
    ) {}

    public getByElection(ulid: string, electedOnly = false): Observable<StatisticResource> {
        const params = HttpParamUtil.create();

        if (electedOnly) {
            params.add('elected', 'true');
        }

        return this.api.get('/statistic_resources/' + ulid, {
            params: params.getHttpParams()
        }).pipe(
            map((res) => res as StatisticResource)
        );
    }
}
