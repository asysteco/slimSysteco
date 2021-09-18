
echo "Cloning Repository"
# Setup git
echo "Create Deploy dir"
$DEPLOY_DIR = "deploy"
mkdir "$DEPLOY_DIR"

echo "Cloning destination git repository"
# Setup git
git config --global user.email "alvaromelkunas9@gmail.com"
git config --global user.name "asysteco"
git clone --single-branch --branch "deploy" "https://asysteco@github.com/asysteco/slimSysteco.git" "$DEPLOY_DIR"
ls -la "$DEPLOY_DIR"


echo "Composer Install"
composer install

echo "Clear deploy dir"
rm -fr "$DEPLOY_DIR"/*

echo "Remove source Files"
rm -fr ./.*

echo "Move Files"
cp -ra ./ "$DEPLOY_DIR"

echo "Change Dir"
cd "$DEPLOY_DIR"

echo "Change branch"
git status

echo "Files that will be pushed:"
ls -la

ORIGIN_COMMIT="https://github.com/$GITHUB_REPOSITORY/commit/$GITHUB_SHA"
COMMIT_MESSAGE="${ORIGIN_COMMIT}"

echo "git add:"
git add .

echo "git status:"
git status

echo "git diff-index:"
# git diff-index : to avoid doing the git commit failing if there are no changes to be commit
git diff-index --quiet HEAD || git commit --message "$COMMIT_MESSAGE"

echo "git push origin:"
# --set-upstream: sets de branch when pushing to a branch that does not exist
git push origin deploy