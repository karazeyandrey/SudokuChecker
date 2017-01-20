# -*- mode: ruby -*-
# vi: set ft=ruby :
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  # All Vagrant configuration is done here. The most common configuration
  # options are documented and commented below. For a complete reference,
  # please see the online documentation at vagrantup.com.

  # Every Vagrant virtual environment requires a box to build off of.
  # config.vm.box = "phusion-open-ubuntu-14.04-amd64"
  # config.vm.box_url = "https://oss-binaries.phusionpassenger.com/vagrant/boxes/latest/ubuntu-14.04-amd64-vbox.box"

  # The url from where the 'config.vm.box' box will be fetched if it
  # doesn't already exist on the user's system.
  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network :forwarded_port, guest: 22, host: 21598

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.box = "obihann/lamp"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.provision "puppet" do |puppet|
    puppet.environment_path = "environments"
    puppet.environment = "sudoku"
  end
end
