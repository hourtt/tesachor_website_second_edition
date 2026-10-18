---
trigger: manual
---

When I explicitly ask you to commit and push my project changes, follow these instructions.

Before committing:

- Check the current branch, working-tree status, configured Git remotes, and upstream branch.
- Review the diffs, including untracked files, so you understand what will be committed.
- Include the intended project changes. Do not accidentally include unrelated existing work, secrets, credentials, .env files, dependencies, or generated files.
- If you find sensitive files, unclear unrelated changes, or changes that could be overwritten, stop and ask me before proceeding.
- Group related changes into logical commits. Keep unrelated changes in separate commits.

Commit messages:

- Every commit message must begin with the appropriate prefix:
    - [ADD] for changes primarily creating new files or features.
    - [UPDATE] for modifications, improvements, refactoring, bug fixes, or UI changes.
    - [REMOVE] for deletions.
- After the prefix, write a concise, specific description. Do not use generic messages such as “update files” or “fix code.”
- If a logical commit includes mixed file changes, choose the prefix that best describes the main purpose of that commit.

For every commit:

- Stage only the files belonging to that logical change.
- Review the staged diff before committing and confirm it matches the intended commit.
- Run appropriate checks when practical.
- Use a separate commit for each unrelated logical group.

After committing:

- Push the successful commits to the current branch’s configured upstream on GitHub.
- Never force-push, amend commits, discard changes, or rewrite history unless I explicitly authorize it.
- If there is no upstream, the branch is diverged, or the push fails, do not try a risky workaround. Explain the problem and ask me how to proceed.

Finally, report the branch, commit hashes and messages, files or change groups included, checks run, and whether the push succeeded.
