# Blacksmith's Project : UserServer

## Why ?

Along the years, I created many projects (never finished one :sweat_smile:).  
Each one got its own user management code.

I have other projects in mind, but I thought this time, I'm going to build an application to avoid re-inventing the wheel !

Many solutions already exists, so why build an other one ?
Because the goal here is not to provide an application for the world to use (still, feel free to use it if you want :smile:).

So, let's talk about the goals of this project:

## Goals:

**Disclaimer:** the word **'good'** below is totally subjective. It refers on my past attempts to achieve those goals.

With this project, I pursue two learning goals:

- Writing _good_ tests.
- Crafting a _good_ architecture.

## How:

### Writing good tests:

My understanding of tests is constantly evolving.
Until now, I always tested classes or methods, mocking dependencies to test only the targeted code.

I feel that this way of testing is not right, since I cannot change the code implementation without changing the mocks in my tests.  
This tells me that I cannot rely on my tests to refactor safely my code, hence, the only "protection" my tests are giving me is the fact that my codebase did not change.
Not a great value for the time invested in writing them.

Right now, I think that those kind of tests should only be used to test code with large combinatorial possibilities, algorithms for example.

So, in this project, I'm going to write tests with a larger scope, a true **unit** test.

This will lead us to the second point:

### Crafting a good architecture:

From the early months of my developer journey, I'm trying to understand what a _good_ architecture is.  
I did not found the solution yet, but still moving forward.

In this project, I want to try a "Port&Adapter" Architecture.

I will put 2 main folders:
- `Domain` will rely on its own code, and contain the `Port` part, interfaces to the external world. It will expose
`UseCases` to be used by the `Infrastructure`.
- `Infrastructure` will implement the `Domain ports` with technical `Adapters`. It will also handle the Input/Output of 
the application, through HTTP and/or CLI.

## Domain

The application will be able to:

- Register a new User.
- Activate a registered User.
- Authenticate an activated User.
- Ask to reset an activated User's password.
- Reset an activated User's password.
- Delete a User.

It will be callable only by other backends through an SDK (next project), providing a secret token through an HTTP Header.

## Visually explained: 
![Image](./Docs/images/project.png)
