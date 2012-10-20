pkgname=bootlist
pkgrel=1
pkgver=1.0
pkgdesc="Pretty directory listing"
arch=('i686' 'x86_64')
url='https://github.com/arcanis/trivia.bootlist/'
license=('BSD')
makedepends=('git')

build() {

    git clone git://github.com/arcanis/trivia.bootlist.git "$startdir/bootlist-repo"
    install -d "$pkgdir/usr/share/webapps/"
    cp -dPr --no-preserve=ownership "$startdir/bootlist-repo/bootlist" "$pkgdir/usr/share/webapps/bootlist"

}
