
echo "Cloning Repository"
# Setup git
CLONE_DIR=$(mktemp -d)

echo "Cloning destination git repository"
# Setup git
git config --global user.email "alvaromelkunas9@gmail.com"
git config --global user.name "asysteco"
git clone --single-branch --branch "deploy" "https://asysteco@github.com/asysteco/slimSysteco.git" "$CLONE_DIR"
ls -la "$CLONE_DIR"

TARGET_DIR=$(mktemp -d)
echo "Copy contents to target git repository"
cp -ra ./ "$TARGET_DIR"
cd "$TARGET_DIR"
mv "./.git" "./git"
mv "./.gitignore" "./gitignore"
mv "./.htaccess" "./htaccess"

rm -fr ./.*

mv "./git" "./.git"
mv "./gitignore" "./.gitignore"
mv "./htaccess" "./.htaccess"

echo "Composer Install"

composer install

echo "Files that will be pushed:"
ls -la

echo ORIGIN_COMMIT="https://github.com/$GITHUB_REPOSITORY/commit/$GITHUB_SHA"
COMMIT_MESSAGE="${ORIGIN_COMMIT}"
COMMIT_MESSAGE="${COMMIT_MESSAGE/\$GITHUB_REF/$GITHUB_REF}"

echo "git add:"
git add .

echo "git status:"
git status

echo "git diff-index:"
# git diff-index : to avoid doing the git commit failing if there are no changes to be commit
git diff-index --quiet HEAD || git commit --message "$COMMIT_MESSAGE"

echo "git push origin:"
# --set-upstream: sets de branch when pushing to a branch that does not exist
git push "https://asysteco@github.com/asysteco/slimSysteco.git" --set-upstream "deploy"