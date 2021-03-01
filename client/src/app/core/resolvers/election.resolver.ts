import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs';
import { ElectionService } from '../services/election.service';
import { Election } from '../models/election.model';

@Injectable()
export class ElectionResolver implements Resolve<Election> {
    constructor(
        private electionService: ElectionService,
    ) { }
    /* eslint-disable */
    public resolve(
        route: ActivatedRouteSnapshot,
        state: RouterStateSnapshot
    ): Observable<Election> {
        return this.electionService.find(route.paramMap.get('electionId'));
    }
    /* eslint-enable */
}
