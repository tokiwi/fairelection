# FairElection
FairElection creates a tool for political organisations, civil society organisations and companies to select candidates according to their self-chosen criteria of diversity/representation. In addition, everyone can use the tool to simulate the results of the latest National Council elections (2019) by modifying these same diversity criteria.

The FairElection tool supports political parties, civil society organisations and companies by monitoring internal selection processes and drawing up lists of candidates. The method works in two steps. First, members, supporters and/or the general public can choose their criteria for representation. What criteria will such a list have to meet? Gender parity, representation of the generations, place of residence, level of education? A first vote is organised to choose these representation criteria.

Secondly, these chosen criteria are applied during an internal selection process. In a second vote, voters choose their favourite candidates. The freedom of choice of voters is not limited. They themselves do not have to create the perfect list that meets the criteria. It is the FairElection algorithm that ensures that the criteria chosen in the first vote are applied to the election result. The group of winning candidates will therefore be the one that satisfies the chosen criteria while respecting the democratic choice of the members.

This way, the winning group of candidates will be satisfying the criteria chosen by the user, while respecting the democratic choice of members. The algorithm provides a mathematical guarantee that the winning group is the one that obtains the most votes while respecting the criteria.

The platform FairElection is available here : https://fairelection.ch

## Solver

This part of FairElection is responsible to solve "mathematically" the linear equation. It takes as input the different criteria set by the user and gives the best solution.

## Backend

This part of FairElection is responsible for business logic. It manages the user profiles, the election instances, the citeria and the business flow.

## Client

This part of FairElection is responsible to display the user interface.

## Setup

To get it working, follow these steps:

[Backend setup](./backend/README.md)

[Client setup](./client/README.md)

## Open Source

FairElection is open source!
