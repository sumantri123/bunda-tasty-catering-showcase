irectly. Instead, install
 the linux-oracle meta-package, which will ensure that upgrades work
 correctly, and that supporting packages are also installed.

Package: linux-modules-5.4.0-1095-gke
Description-md5: af2cae998728695f0e6392e063cec7fe
Description-en: Linux kernel extra modules for version 5.4.0 on 64 bit x86 SMP
 Contains the corresponding System.map file, the modules built by the
 packager, and scripts that try to ensure that the system is not left in an
 unbootable state after an update.
 .
 Supports amd64 arm64 processors.
 .
 Geared toward GKE systems.
 .
 You likely do not want to install this package directly. Instead, install
 the linux-gke meta-package, which will ensure that upgrades work
 correctly, and that supporting packages are also installed.

Package: linux-modules-5.4.0-1095-kvm
Description-md5: 03f64ecc39ead7bdc04f5eb28cec525e
Description-en: Linux kernel extra modules for version 5.4.0 on 64 bit x86 SMP
 Contains the corresponding System.map file, the modules built by the
 packager, and scripts that try to ensure that the system is not left in an
 unbootable state after an update.
 .
 Supports amd64 processors.
 .
 Geared toward virtual systems.
 .
 You likely do not want to install this package directly. Instead, install
 the linux-kvm meta-package, whi