# Teradeveloper First Integration

## Contributing & Branching Workflow

This repository follows a strict 3-branch workflow to ensure stability and smooth deployments:

1. **`main` / `production`**: The stable, production-ready source of truth.
2. **`stage`**: The pre-production environment for final QA and client review.
3. **`develop`**: The active integration branch where all new features are merged and tested first.

### Developer Workflow
* **Start Work**: Always create your feature or bugfix branch by checking out from the **`main`** (production) branch.
* **Submit for Testing**: Once your work is ready, open a Pull Request (PR) against the **`develop`** branch.
* **Submit for QA**: After passing tests in `develop`, open a PR to merge into the **`stage`** branch for final verification.
* **Release**: Once fully approved on `stage`, the feature is merged into **`main`** for production deployment.
