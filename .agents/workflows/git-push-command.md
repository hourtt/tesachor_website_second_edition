---
description: Review project changes, commit them using the Git Commit and Push rule, and push to the current branch.
---

# Commit and push project changes

Follow the project's existing Git Commit and Push rule.

1. Review the current branch, working-tree status, remotes, and diffs.
2. Identify the intended changes. Do not include secrets, credentials, or unrelated existing work. Ask me before proceeding if anything is ambiguous or risky.
3. Group related changes into logical commits.
4. Stage and review each group before committing. Use the required [ADD], [UPDATE], or [REMOVE] prefix and a specific commit message.
5. Push the commits to the current branch's configured upstream. Do not force-push or rewrite history.
6. If the push cannot be completed safely, stop and explain why.
7. Report the commit hashes and messages, checks run, and push result.
