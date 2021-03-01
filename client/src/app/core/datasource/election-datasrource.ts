import { CollectionViewer, DataSource } from '@angular/cdk/collections';
import { BehaviorSubject, Observable } from 'rxjs';
import { Election } from '../models/election.model';
import { ElectionService } from '../services/election.service';
import { environment } from '../../../environments/environment';
import { Paginator } from '../models/paginator.model';

export class ElectionDatasrource implements DataSource<any>  {
    public totalItems = 0;
    public itemsPerPage = 0;

    private electionSubject = new BehaviorSubject<Election[]>([]);
    private loadingSubject = new BehaviorSubject<boolean>(false);

    // eslint-disable-next-line
    public loading$ = this.loadingSubject.asObservable();

    constructor(
        private electionService: ElectionService
    ) {}

    /* eslint-disable */
    connect(): Observable<Election[]> {
      return this.electionSubject.asObservable();
    }

    disconnect(collectionViewer: CollectionViewer): void {
      this.electionSubject.complete();
      this.loadingSubject.complete();
    }
    /* eslint-enable */
    public loadElections(
        page = 1,
        itemsPerPage: number = environment.itemsPerPage,
    ): void {
        this.electionService.findAllPaginated(page, itemsPerPage).subscribe((paginator: Paginator) => {
            this.totalItems = paginator['hydra:totalItems'];
            this.itemsPerPage = itemsPerPage;
            this.electionSubject.next(paginator['hydra:member']);
        });
    }
}
