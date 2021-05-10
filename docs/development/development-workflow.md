# Development Workflow

This document explains how the development workflow followed by the Astra Team.

### Reporting the issues

We use a private issue tracker (Jira) to manage the issues. You are still welcome to open issues on GitHub as well, When you open any issue Astra team members will create a relevant issue in Jira for this and close the GitHub issue.

### Branches

All the changes that are to be added to the Astra theme should come in a pull request, No changes should be pushed directly to the main branches.

Main (protected) branches in Astra are -
1. `master` - This branch always has the stable release that is available on WordPres.org
2. `dev` (This will be renamed to `release-candidate`) - The code is merged into this branch by the release squad just before the release. This branch is used by the QA to do final release testing.
3. `next-release` - All the pull reuqests should be made against this branch.
4. `development` - The developers will manually merge the code into this branch to have it deployed to the dev site for testing.

If you an code contributor then you can skip the Step #4, This will be done for you by one of the Astra team members.

### Creating a pull request

_If you are contributing code to Astra, you can skip this part. Astra team members can update all the required info for the PR_

All the pull requests should have a Jira issue key as a prefix. The format for PR titles is -

```
[Jira Issue Key] <short description of the PR>
```

This ensures that the PR is automatically linked to the relevant issue in Jira.

### Complete dev workflow

_If you are contributing code to Astra, you can skip this part._

1. Create a Pull request for a fix against the `next-release` branch.
2. When the pull request is ready to be tested, merge the code manually to the `development` branch. This will deploy the code to the dev site.
3. Test the code you just deployed on the dev site, Add screenshots and link to your tested scenerios in the issue comment on Jira.
     - If you notice any bugs, commit the fixes into same PR you created against the `next-release` branch.
     - Copy the code new fixes in the `development` branch again, test and make sure this is ready for QA.
     - Add your testing notes in the issue comment along with relevant screenshots and link to your test scenerios.
4. Move the issue to QA column for QA team to test the issue.
5. If QA team reports any improvements, you can do them in the same branch and manually copy the code into the `development` branch.
6. Test your changes on the dev site and add comment on the issue when you have verified the changes along with relevant screenshots and links.
7. Once the task is approved by QA, the PR will be merged to `next-release` branch.
