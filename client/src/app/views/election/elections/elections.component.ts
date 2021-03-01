import { AfterViewInit, Component, OnInit, ViewChild } from '@angular/core';
import { ElectionService } from '../../../core/services/election.service';
import { Observable } from 'rxjs';
import { Election } from '../../../core/models/election.model';
import { IriUtil } from '../../../core/utils/iri.util';
import { MatPaginator } from '@angular/material/paginator';
import { ElectionDatasrource } from '../../../core/datasource/election-datasrource';
import { NotificationService } from '../../../core/services/notification.service';
import { TitleService } from '../../../core/services/title.service';

@Component({
    selector: 'app-elections',
    templateUrl: './elections.component.html',
    styleUrls: ['./elections.component.scss']
})
export class ElectionsComponent implements OnInit, AfterViewInit {

    @ViewChild(MatPaginator, {static: false})
    public paginator: MatPaginator;

    public dataSource: ElectionDatasrource;

    public elections$: Observable<Election[]>;

    constructor(
        private electionService: ElectionService,
        private notificationService: NotificationService,
        private titleService: TitleService,
    ) {
        this.titleService.setTitle('component.elections.title1');

        this.dataSource = new ElectionDatasrource(this.electionService);
        this.dataSource.loadElections();
        this.elections$ = this.dataSource.connect();
    }

    ngOnInit(): void {

    }

    public delete(election: Election): void {
        this.electionService.delete(IriUtil.extractId(election['@id'])).subscribe(() => {
            this.notificationService.success('toast.election_deleted');

            this.loadElectionsPaginated();
        });
    }

    public ngAfterViewInit(): void {
        this.paginator.page.subscribe(() => this.loadElectionsPaginated());
    }

    private loadElectionsPaginated(): void {
        this.dataSource.loadElections(
            this.paginator.pageIndex + 1,
            this.paginator.pageSize
        );
    }
}
