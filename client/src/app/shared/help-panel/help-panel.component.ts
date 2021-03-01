import { Component, Input, OnInit } from '@angular/core';
import { Election } from '../../core/models/election.model';

@Component({
    selector: 'app-help-panel',
    templateUrl: './help-panel.component.html',
    styleUrls: ['./help-panel.component.scss']
})
export class HelpPanelComponent implements OnInit {

    @Input()
    public election: Election;

    events: string[] = [];
    opened: boolean;

    constructor() { }

    ngOnInit(): void {
    }

}
