workflow "on pull request merge, delete the branch" {
  resolves = ["branch cleanup"]
  on = "pull_request"
}

action "branch cleanup" {
  uses = "jessfraz/branch-cleanup-action@master"
  secrets = ["GITHUB_TOKEN"]
}
