
echo "Cloning Repository"
# Setup git
CLONE_DIR=$(mktemp -d)

git config --global user.email "alvaromelkunas9@gmail.com"
git config --global user.name "asysteco"
git clone --single-branch --branch "deploy" "https://asysteco:$API_TOKEN_GITHUB@github.com/asysteco/slimSysteco.git" "$CLONE_DIR"
ls -la "$CLONE_DIR"

echo "Composer Install"

composer install

cp -r ./ "$CLONE_DIR"
cp -ari ./.git "$CLONE_DIR"

ls -la "$CLONE_DIR"